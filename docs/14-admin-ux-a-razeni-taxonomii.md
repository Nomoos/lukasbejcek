# 14 – Vylepšení administračního rozhraní a řazení taxonomií

## 1. Technická dokumentace (pro písemnou práci)

### 1.1 Popis řešeného problému

WordPress ve výchozím nastavení řadí termy taxonomií abecedně. V případě fotbalového klubu TJ Slavoj Mýto to znamená, že v každém dropdown filtru i sloupci administrace se kategorie „Dorost" zobrazí před „Muži A", protože písmeno D předchází písmenu M. Tento výchozí stav neodpovídá realitě sportovního klubu, kde existuje ustálená hierarchie týmů — od nejvyšší soutěže (Muži A) po nejnižší mládežnické kategorie (Minipřípravka).

Druhý problém se týkal administračního přehledu vlastních typů obsahu (custom post types). WordPress sice automaticky generuje sloupce pro přiřazené taxonomie (díky parametru `show_admin_column => true`), ale neposkytuje dropdown filtry pro rychlé filtrování záznamů. Správce obsahu, který spravuje desítky zápasů nebo hráčů, musel ručně procházet celý seznam bez možnosti omezit zobrazení na konkrétní sezónu nebo tým.

Třetí problém se týkal kategorie „Stará garda". Tato kategorie se vztahuje výhradně ke galerii (historické fotografie), ale neměla by se zobrazovat v kontextu zápasů, týmů ani hráčů, kde nemá logický smysl. WordPress nemá nativní mechanismus pro podmíněné zobrazení termů taxonomie podle kontextu.

### 1.2 Proč výchozí chování WordPressu nestačilo

WordPress nabízí pro taxonomie dva hlavní mechanismy zobrazení v administraci: automatické sloupce (`show_admin_column`) a vestavěné filtrování pro standardní kategorie a štítky. Vlastní taxonomie registrované pomocí `register_taxonomy()` však automatické dropdown filtry nezískají — ty je nutné implementovat ručně prostřednictvím hooku `restrict_manage_posts`.

Řazení termů je v jádře WordPressu řízeno parametrem `orderby` funkce `get_terms()`, který podporuje hodnoty `name`, `slug`, `term_id`, `count` a další. Žádná z nich neumožňuje definovat vlastní pořadí odpovídající sportovní hierarchii. Řešení pomocí číselného prefixu v názvu termu (např. „01 – Muži A") by bylo technicky funkční, ale vizuálně nepřijatelné jak v administraci, tak na front-endu.

Pro podmíněné zobrazení termů (Stará garda pouze v galerii) WordPress neposkytuje žádný vestavěný mechanismus. Taxonomie jsou buď registrovány na daném post type, nebo ne — není možné říci „tento term zobrazuj pouze v kontextu galerie".

### 1.3 Návrh řešení

Řešení je postaveno na třech vzájemně propojených vrstvách:

**Vrstva 1 — Deklarativní definice pořadí.** Funkce `slavoj_kategorie_poradi()` v souboru `functions.php` vrací asociativní pole, kde klíčem je slug termu a hodnotou jeho zobrazovaný název. Pořadí prvků v poli definuje kanonické řazení:

```php
function slavoj_kategorie_poradi() {
    return array(
        'muzi-a'            => 'Muži A',
        'muzi-b'            => 'Muži B',
        'stara-garda'       => 'Stará garda',
        'dorost'            => 'Dorost',
        'starsi-zaci'       => 'Starší žáci',
        'mladsi-zaci'       => 'Mladší žáci',
        'starsi-pripravka'  => 'Starší přípravka',
        'mladsi-pripravka'  => 'Mladší přípravka',
        'mini-pripravka'    => 'Mini přípravka',
    );
}
```

Toto pole slouží jako jediný zdroj pravdy (single source of truth) pro pořadí kategorií v celém projektu. Přidání nové kategorie znamená přidat jeden řádek na správnou pozici.

**Vrstva 2 — Řadící funkce.** Funkce `slavoj_sort_tymy()` přijímá pole objektů `WP_Term` a seřadí je podle indexu v kanonickém pořadí. Termy, které nejsou v definici obsaženy, jsou zařazeny na konec seznamu (index 999), takže systém nezhavaruje při přidání nového termu, který dosud nebyl do mapy zařazen.

**Vrstva 3 — Globální filtr s kontextovým filtrováním.** Funkce `slavoj_global_sort_kategorie_tymu()` je zaregistrována na WordPress filtr `get_terms` a automaticky se uplatní při každém dotazu na taxonomii `kategorie-tymu` — v administraci, na front-endu i v REST API. Po seřazení termů funkce detekuje aktuální kontext (admin screen, front-end archiv, detail příspěvku) a odfiltruje termy, které do daného kontextu nepatří.

Kontextová pravidla jsou definována deklarativně ve funkci `slavoj_kategorie_kontextove_vyjimky()`:

```php
function slavoj_kategorie_kontextove_vyjimky() {
    return array(
        'stara-garda' => array( 'galerie' ),
    );
}
```

Každý záznam říká: „term s tímto slugem zobrazuj pouze v kontextu uvedených post types." Přidání další výjimky znamená přidat jeden řádek.

### 1.4 Implementace dropdown filtrů

Administrační filtry jsou implementovány v pluginu `slavoj-custom-fields` prostřednictvím hooku `restrict_manage_posts`. Centrální funkce `slavoj_admin_filters()` obsahuje konfigurační mapu, která pro každý typ obsahu definuje, které taxonomie se mají nabízet k filtrování:

```php
$tax_filters = array(
    'zapas'   => array( 'sezona', 'kategorie-tymu' ),
    'hrac'    => array( 'sezona' ),
    'tym'     => array( 'sezona' ),
    'galerie' => array( 'sezona', 'kategorie-tymu' ),
);
```

Pro taxonomické filtry WordPress zpracovává filtrování automaticky — stačí vykreslit HTML element `<select>` se správným atributem `name` odpovídajícím slugu taxonomie. WordPress při načtení stránky rozpozná query parametr a automaticky přidá `tax_query` do hlavního dotazu.

Speciální případ představuje filtrování hráčů podle týmu. Hráči jsou s týmy propojeni prostřednictvím meta pole `tym_slug`, nikoli taxonomie. Proto je pro ně implementován samostatný dropdown `slavoj_admin_filter_tym_slug()`, který načte všechny publikované týmy a zobrazí je jako volby. Filtrování je zpracováno hookem `pre_get_posts`, kde se přidá `meta_query` na pole `tym_slug`.

### 1.5 Implementace vlastních sloupců

Pro zápasy a hráče jsou v administraci definovány vlastní sloupce prostřednictvím filtrů `manage_{post_type}_posts_columns` a `manage_{post_type}_posts_custom_column`:

**Zápasy** zobrazují sloupce Datum zápasu, Tým a Skóre. Datum je formátováno z interního formátu `Y-m-d` do čitelné podoby `j. n. Y` (např. „15. 3. 2026"). Sloupec Datum je řaditelný — kliknutím na záhlaví se zápasy seřadí chronologicky.

**Hráči** zobrazují sloupce Číslo dresu, Pozice a Tým. Číslo dresu je řaditelné numericky (nikoli abecedně, aby 2 nebylo za 19). Sloupec Tým zobrazuje čitelný název získaný funkcí `slavoj_get_team_display_name()`, která převádí interní slug (např. `muzi-a`) na zobrazovaný text („Muži A").

### 1.6 Výsledné chování

Následující tabulka shrnuje, jaké filtry a sloupce jsou dostupné pro jednotlivé typy obsahu:

| Typ obsahu | Dropdown filtry | Vlastní sloupce | Řaditelné sloupce |
|---|---|---|---|
| Zápasy | Sezóna, Kategorie týmu | Datum, Tým, Skóre | Datum |
| Hráči | Sezóna, Tým (meta) | Číslo dresu, Pozice, Tým | Číslo dresu, Tým |
| Týmy | Sezóna | — (pouze auto-taxonomie) | — |
| Galerie | Sezóna, Kategorie týmu | — (pouze auto-taxonomie) | — |

Kategorie „Stará garda" se zobrazuje výhradně v kontextu galerie. Ve filtrech i sloupcích zápasů, hráčů a týmů je automaticky odfiltrována globálním filtrem.

### 1.7 Výhody a omezení řešení

**Výhody:**

Centralizace řadící logiky zajišťuje konzistenci. Pořadí kategorií je definováno na jednom místě a automaticky se uplatní ve všech kontextech — v administraci, na front-endu, v REST API. Při přidání nové kategorie stačí upravit jednu funkci a změna se projeví všude.

Deklarativní přístup ke kontextovým výjimkám odděluje definici pravidel od jejich vynucování. Funkce `slavoj_kategorie_kontextove_vyjimky()` slouží jako čitelný přehled pravidel, zatímco `slavoj_filtruj_kategorie_dle_kontextu()` je vykonává. Přidání nového pravidla nevyžaduje úpravu filtrační logiky.

Řešení nepotřebuje žádné externí pluginy. Veškerá funkčnost je postavena na nativních WordPress hookách (`get_terms`, `get_the_terms`, `restrict_manage_posts`, `pre_get_posts`, `manage_*_posts_columns`).

**Omezení:**

Globální filtr na `get_terms` se uplatní při každém dotazu na taxonomii `kategorie-tymu`, včetně kontextů, které nemůže spolehlivě identifikovat (například volání `get_terms()` z externího pluginu mimo hlavní šablonu). V takovém případě je kontext vyhodnocen jako neznámý a kontextově omezené termy jsou odstraněny — to odpovídá principu konzervativního výchozího chování.

Propojení hráčů s týmy prostřednictvím meta pole `tym_slug` (místo přímého post-to-post vztahu) vyžaduje při filtrování vlastní `meta_query`, což je méně efektivní než taxonomický dotaz. Pro aktuální rozsah dat (desítky hráčů) je tento rozdíl zanedbatelný.

---

## 2. Podklad pro ústní obhajobu (co říct)

> Jedním z problémů, které jsem řešil, bylo to, jak se v administraci WordPressu zobrazují kategorie týmů. WordPress je ve výchozím stavu řadí abecedně — takže „Dorost" byl vždycky první a „Muži A" až někde uprostřed. To je pro správce obsahu matoucí, protože ve fotbalovém klubu existuje jasná hierarchie: nejdřív A-tým, pak B-tým, pak mládež.
>
> Řešení jsem postavil tak, že na jednom místě v kódu existuje pole, které definuje správné pořadí všech kategorií. Z tohoto pole vychází řadící funkce, která je napojená na globální WordPress filtr. To znamená, že kdekoliv se v celém systému načítají kategorie týmů — ať už v administraci, na webu, nebo přes API — automaticky se seřadí správně. Nemusím řazení řešit zvlášť na každé stránce.
>
> Navíc jsem řešil situaci s kategorií „Stará garda". Ta se týká jen galerie — historické fotky. V zápasech nebo u hráčů nemá smysl ji zobrazovat. Takže jsem do toho globálního filtru přidal kontextové filtrování. Systém detekuje, jestli se zrovna nachází v administraci galerie nebo zápasů, a podle toho Starou gardu buď zobrazí, nebo skryje.
>
> Důležité je, že pravidla jsou definovaná deklarativně — tedy jako data, ne jako podmínky rozsypané po kódu. Pokud by přibyla další podobná výjimka, stačí přidat jeden řádek do jedné funkce.
>
> Kromě řazení jsem do administrace přidal dropdown filtry, aby správce mohl rychle najít třeba všechny zápasy Muži A v sezóně 2025/26. A u hráčů jsem vyřešil speciální případ, kdy propojení s týmem není přes taxonomii, ale přes meta pole — takže jsem musel filtr napsat ručně a zpracovat ho přes hook `pre_get_posts`.
>
> Celé řešení je postavené čistě na nativních WordPress hookách, bez externích pluginů.

---

## 3. Průvodce ukázkou při obhajobě (co předvést)

### Krok 1 — Otevřít administraci zápasů

1. Otevřít `wp-admin` → **Zápasy** → **Všechny zápasy**.
2. Ukázat dropdown filtry nad tabulkou: **Sezóna** a **Kategorie týmu**.
3. Poukázat na to, že kategorie jsou seřazeny správně: Muži A → Muži B → Dorost → … a že „Stará garda" zde **chybí** (záměrně).
4. Vybrat konkrétní sezónu a kliknout na „Filtrovat" — tabulka se zúží.
5. Kliknout na záhlaví sloupce **Datum zápasu** — ukázat, že se řadí chronologicky.

**Říct:** „Filtry umožňují správci obsahu rychle najít konkrétní zápasy. Řazení kategorií odpovídá hierarchii klubu, ne abecedě."

### Krok 2 — Otevřít administraci galerie

1. Přejít na **Galerie** → **Všechna alba**.
2. Otevřít dropdown **Kategorie týmu**.
3. Ukázat, že zde se „Stará garda" **zobrazuje** — na třetí pozici za Muži B.

**Říct:** „Stejný filtr, ale jiný kontext. Systém automaticky rozpozná, že jsme v galerii, a Starou gardu zobrazí. V zápasech ji skryl."

### Krok 3 — Otevřít administraci hráčů

1. Přejít na **Hráči** → **Všichni hráči**.
2. Ukázat sloupce: **Číslo dresu**, **Pozice**, **Tým** — tým zobrazuje čitelný název, ne interní slug.
3. Ukázat dropdown filtry: **Sezóna** a **Tým** (druhý dropdown načítá data z CPT Týmy).
4. Vybrat konkrétní tým — tabulka se zúží na hráče tohoto týmu.
5. Kliknout na záhlaví **Číslo dresu** — ukázat numerické řazení (2 před 10, ne za 19).

**Říct:** „Hráči jsou s týmy propojeni přes meta pole, ne přes taxonomii. Proto jsem musel filtrování implementovat ručně přes `pre_get_posts` a `meta_query`."

### Krok 4 — Ukázat kód (volitelné, pokud komise požádá)

Otevřít soubor `functions.php` a ukázat:

1. **`slavoj_kategorie_poradi()`** (řádek ~801) — „Toto pole definuje pořadí. Jeden zdroj pravdy pro celý systém."
2. **`slavoj_kategorie_kontextove_vyjimky()`** (řádek ~865) — „Zde je deklarativně řečeno, že Stará garda patří jen do galerie. Přidání další výjimky = jeden řádek."
3. **`slavoj_global_sort_kategorie_tymu()`** (řádek ~895) — „Globální filtr na `get_terms`. Seřadí a odfiltruje automaticky, kdekoliv v systému."

**Říct:** „Architektura odděluje definici pravidel od jejich vynucování. Údržba je jednoduchá — správce kódu nemusí hledat, kde všude se řazení řeší."
