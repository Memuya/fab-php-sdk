<?php

namespace Integration\Adapters\TheFabCube\SearchCriteria\Cards;

use Memuya\Fab\Adapters\TheFabCube\SearchCriteria\Cards\CardsSearchCriteria;
use Memuya\Fab\Enums\Pitch;
use Memuya\Fab\Enums\Rarity;
use Memuya\Fab\Enums\Set;
use Memuya\Fab\Utilities\CompareWithOperator;
use PHPUnit\Framework\TestCase;

final class CardsSearchCriteriaTest extends TestCase
{
    public function testCanSetValidName()
    {
        $name = 'test';
        $config = new CardsSearchCriteria(['name' => $name]);
        $this->assertSame($name, $config->name);
    }

    public function testCanSetValidPitch()
    {
        $config = new CardsSearchCriteria(['pitch' => new CompareWithOperator(Pitch::One)]);

        $this->assertSame(Pitch::One, $config->pitch->value);
    }

    public function testCanSetValidCost()
    {
        $cost = '3';
        $config = new CardsSearchCriteria(['cost' => $cost]);
        $this->assertSame($cost, $config->cost);
    }

    public function testCanSetValidSetId()
    {
        $cardId = 'set123';
        $config = new CardsSearchCriteria(['card_id' => $cardId]);
        $this->assertSame($cardId, $config->card_id);
    }

    public function testCanSetValidPower()
    {
        $power = '5';
        $config = new CardsSearchCriteria(['power' => $power]);
        $this->assertSame($power, $config->power);
    }

    public function testCanSetValidUniqueId()
    {
        $uniqueId = 'unique456';
        $config = new CardsSearchCriteria(['unique_id' => $uniqueId]);
        $this->assertSame($uniqueId, $config->unique_id);
    }

    public function testCanSetValidDefense()
    {
        $defense = '3';
        $config = new CardsSearchCriteria(['defense' => $defense]);
        $this->assertSame($defense, $config->defense);
    }

    public function testCanSetValidHealth()
    {
        $health = '7';
        $config = new CardsSearchCriteria(['health' => $health]);
        $this->assertSame($health, $config->health);
    }

    public function testCanSetValidIntelligence()
    {
        $intelligence = '4';
        $config = new CardsSearchCriteria(['intelligence' => $intelligence]);
        $this->assertSame($intelligence, $config->intelligence);
    }

    public function testCanSetValidArcane()
    {
        $arcane = '2';
        $config = new CardsSearchCriteria(['arcane' => $arcane]);
        $this->assertSame($arcane, $config->arcane);
    }

    public function testCanSetValidTypes()
    {
        $types = ['attack', 'action'];
        $config = new CardsSearchCriteria(['types' => $types]);
        $this->assertSame($types, $config->types);
    }

    public function testCanSetValidCardKeywords()
    {
        $keywords = ['keyword1', 'keyword2'];
        $config = new CardsSearchCriteria(['card_keywords' => $keywords]);
        $this->assertSame($keywords, $config->card_keywords);
    }

    public function testCanSetValidAbilitiesAndEffects()
    {
        $effects = ['effect1', 'effect2'];
        $config = new CardsSearchCriteria(['abilities_and_effects' => $effects]);
        $this->assertSame($effects, $config->abilities_and_effects);
    }

    public function testCanSetValidAbilityAndEffectKeywords()
    {
        $keywords = ['ability1', 'effect2'];
        $config = new CardsSearchCriteria(['ability_and_effect_keywords' => $keywords]);
        $this->assertSame($keywords, $config->ability_and_effect_keywords);
    }

    public function testCanSetValidGrantedKeywords()
    {
        $grantedKeywords = ['granted1', 'granted2'];
        $config = new CardsSearchCriteria(['granted_keywords' => $grantedKeywords]);
        $this->assertSame($grantedKeywords, $config->granted_keywords);
    }

    public function testCanSetValidRemovedKeywords()
    {
        $removedKeywords = ['removed1', 'removed2'];
        $config = new CardsSearchCriteria(['removed_keywords' => $removedKeywords]);
        $this->assertSame($removedKeywords, $config->removed_keywords);
    }

    public function testCanSetValidInteractsWithKeywords()
    {
        $keywords = ['keyword1', 'keyword3'];
        $config = new CardsSearchCriteria(['interacts_with_keywords' => $keywords]);
        $this->assertSame($keywords, $config->interacts_with_keywords);
    }

    public function testCanSetValidFunctionalText()
    {
        $text = 'Some functional text';
        $config = new CardsSearchCriteria(['functional_text' => $text]);
        $this->assertSame($text, $config->functional_text);
    }

    public function testCanSetValidFunctionalTextPlain()
    {
        $text = 'Plain functional text';
        $config = new CardsSearchCriteria(['functional_text_plain' => $text]);
        $this->assertSame($text, $config->functional_text_plain);
    }

    public function testCanSetValidTypeText()
    {
        $typeText = 'Action';
        $config = new CardsSearchCriteria(['type_text' => $typeText]);
        $this->assertSame($typeText, $config->type_text);
    }

    public function testCanSetValidSet()
    {
        $config = new CardsSearchCriteria(['set' => Set::Monarch]);
        $this->assertSame(Set::Monarch, $config->set);
    }

    public function testCanSetValidRarity()
    {
        $config = new CardsSearchCriteria(['rarity' => Rarity::Fabled]);
        $this->assertSame(Rarity::Fabled, $config->rarity);
    }

    public function testCanSetValidBooleanFields()
    {
        $fields = [
            'played_horizontally' => true,
            'blitz_legal' => false,
            'cc_legal' => true,
            'commoner_legal' => false,
            'll_legal' => true,
            'blitz_living_legend' => false,
            'cc_living_legend' => true,
            'blitz_banned' => false,
            'cc_banned' => true,
            'commoner_banned' => false,
            'll_banned' => true,
            'upf_banned' => false,
            'blitz_suspended' => true,
            'cc_suspended' => false,
            'commoner_suspended' => true,
            'll_restricted' => false,
        ];

        foreach ($fields as $field => $value) {
            $config = new CardsSearchCriteria([$field => $value]);

            $this->assertSame($value, $config->{$field});
        }
    }
}
