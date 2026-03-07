<?php

namespace Memuya\Fab\Adapters\TheFabCube\SearchCriteria;

use Memuya\Fab\Enums\Set;
use Memuya\Fab\Enums\Pitch;
use Memuya\Fab\Enums\Rarity;
use Memuya\Fab\Attributes\Filter;
use Memuya\Fab\Readers\SearchCriteria;
use Memuya\Fab\Adapters\TheFabCube\Filters;
use Memuya\Fab\Utilities\CompareWithOperator;

class TheFabCubeSearchCriteria extends SearchCriteria
{
    /**
     * The name to filter by.
     *
     * @var string
     */
    #[Filter(Filters\NameFilter::class)]
    public string $name;

    /**
     * The pitch to filter by.
     *
    * @var CompareWithOperator<Pitch>
     */
    #[Filter(Filters\PitchFilter::class)]
    public CompareWithOperator $pitch;

    /**
     * The cost to filter by.
     *
     * @var string
     */
    #[Filter(Filters\CostFilter::class)]
    public string $cost;

    /**
     * The card ID to filter by.
     *
     * @var string
     */
    #[Filter(Filters\CardIdFilter::class)]
    public string $card_id;

    /**
     * The power to filter by.
     *
     * @var string
     */
    #[Filter(Filters\PowerFilter::class)]
    public string $power;

    /**
     * The unique ID to filter by.
     *
     * @var string
     */
    #[Filter(Filters\UniqueIdFilter::class)]
    public string $unique_id;

    /**
     * The defense to filter by.
     *
     * @var string
     */
    #[Filter(Filters\DefenseFilter::class)]
    public string $defense;

    /**
     * The health to filter by.
     *
     * @var string
     */
    #[Filter(Filters\HealthFilter::class)]
    public string $health;

    /**
     * The intelligence to filter by.
     *
     * @var string
     */
    #[Filter(Filters\IntelligenceFilter::class)]
    public string $intelligence;

    /**
     * The arcane to filter by.
     *
     * @var string
     */
    #[Filter(Filters\ArcaneFilter::class)]
    public string $arcane;

    /**
     * The types to filter by.
     *
     * @var array<string>
     */
    #[Filter(Filters\TypeFilter::class)]
    public array $types;

    /**
     * The card keywords to filter by.
     *
     * @var array<string>
     */
    #[Filter(Filters\CardKeywordsFilter::class)]
    public array $card_keywords;

    /**
     * The abilities and effects to filter by.
     *
     * @var array<string>
     */
    #[Filter(Filters\AbilitiesAndEffectsFilter::class)]
    public array $abilities_and_effects;

    /**
     * The ability and effect keywords to filter by.
     *
     * @var array<string>
     */
    #[Filter(Filters\AbilitiesAndEffectsKeywordsFilter::class)]
    public array $ability_and_effect_keywords;

    /**
     * The granted keywords to filter by.
     *
     * @var array<string>
     */
    #[Filter(Filters\GrantedKeywordsFilter::class)]
    public array $granted_keywords;

    /**
     * The removed keywords to filter by.
     *
     * @var array<string>
     */
    #[Filter(Filters\RemovedKeywordsFilter::class)]
    public array $removed_keywords;

    /**
     * The keywords the card interacts with to filter by.
     *
     * @var array<string>
     */
    #[Filter(Filters\InteractsWithKeywordsFilter::class)]
    public array $interacts_with_keywords;

    /**
     * The functional text to filter by.
     *
     * @var string
     */
    #[Filter(Filters\FunctionalTextFilter::class)]
    public string $functional_text;

    /**
     * The plain functional text to filter by.
     *
     * @var string
     */
    #[Filter(Filters\FunctionalTextPlainFilter::class)]
    public string $functional_text_plain;

    /**
     * The type text to filter by.
     *
     * @var string
     */
    #[Filter(Filters\TypeTextFilter::class)]
    public string $type_text;

    /**
     * Whether the card is played horizontally to filter by.
     *
     * @var bool
     */
    #[Filter(Filters\PlayedHorizontallyFilter::class)]
    public bool $played_horizontally;

    /**
     * Whether the card is Blitz legal to filter by.
     *
     * @var bool
     */
    #[Filter(Filters\BlitzLegalFilter::class)]
    public bool $blitz_legal;

    /**
     * Whether the card is CC legal to filter by.
     *
     * @var bool
     */
    #[Filter(Filters\CcLegalFilter::class)]
    public bool $cc_legal;

    /**
     * Whether the card is Commoner legal to filter by.
     *
     * @var bool
     */
    #[Filter(Filters\CommonerLegalFilter::class)]
    public bool $commoner_legal;

    /**
     * Whether the card is LL legal to filter by.
     *
     * @var bool
     */
    #[Filter(Filters\LlLegal::class)]
    public bool $ll_legal;

    /**
     * Whether the card is a Blitz Living Legend to filter by.
     *
     * @var bool
     */
    #[Filter(Filters\BlitzLivingLegendFilter::class)]
    public bool $blitz_living_legend;

    /**
     * Whether the card is a CC Living Legend to filter by.
     *
     * @var bool
     */
    #[Filter(Filters\CcLivingLegendFilter::class)]
    public bool $cc_living_legend;

    /**
     * Whether the card is banned in Blitz to filter by.
     *
     * @var bool
     */
    #[Filter(Filters\BlitzBannedFilter::class)]
    public bool $blitz_banned;

    /**
     * Whether the card is banned in CC to filter by.
     *
     * @var bool
     */
    #[Filter(Filters\CcBannedFilter::class)]
    public bool $cc_banned;

    /**
     * Whether the card is banned in Commoner to filter by.
     *
     * @var bool
     */
    #[Filter(Filters\CommonerBannedFilter::class)]
    public bool $commoner_banned;

    /**
     * Whether the card is banned in LL to filter by.
     *
     * @var bool
     */
    #[Filter(Filters\LlBannedFilter::class)]
    public bool $ll_banned;

    /**
     * Whether the card is banned in UPF to filter by.
     *
     * @var bool
     */
    #[Filter(Filters\UpfBannedFilter::class)]
    public bool $upf_banned;

    /**
     * Whether the card is suspended in Blitz to filter by.
     *
     * @var bool
     */
    #[Filter(Filters\BlitzSuspendedFilter::class)]
    public bool $blitz_suspended;

    /**
     * Whether the card is suspended in CC to filter by.
     *
     * @var bool
     */
    #[Filter(Filters\CcSuspendedFilter::class)]
    public bool $cc_suspended;

    /**
     * Whether the card is suspended in Commoner to filter by.
     *
     * @var bool
     */
    #[Filter(Filters\CommonerSuspendedFilter::class)]
    public bool $commoner_suspended;

    /**
     * Whether the card is restricted in LL to filter by.
     *
     * @var bool
     */
    #[Filter(Filters\LlRestrictedFilter::class)]
    public bool $ll_restricted;

    /**
     * The set to filter by.
     *
     * @var Set
     */
    #[Filter(Filters\SetFilter::class)]
    public Set $set;

    /**
     * The rarity to filter by.
     *
     * @var Rarity
     */
    #[Filter(Filters\RarityFilter::class)]
    public Rarity $rarity;
}
