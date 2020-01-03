<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Class PolicyText
 * @package App\Controller
 */
class PolicyText
{
    /**
     * Main piece of text
     */
    const TEXT = '[name.plaintiffs] [plaintiffs] [plaintiffs] this action seeking to enforce [plaintiffs] right to privacy under the consumer-privacy provisions of the Telephone Consumer Protection Act (“TCPA”), 47 U.S.C. § 227. <br>[name.defendants] [defendants] violated the TCPA by using an automated dialing system, or “ATDS”, to send telemarketing text messages to [plaintiffs] cellular telephone [plaintiffs] for the purposes of advertising [defendants] goods and services[TCPA][DNCR][IDNCL][TIAA]';

    /**
     * Word 'and' const
     */
    const AND = 'and';

    /**
     * 'TCPA' piece
     */
    const TCPA = '. Further violating the TCPA, [defendants] sent multiple text messages to [plaintiffs]';

    /**
     * 'DNCR' piece
     */
    const DNCR = ' despite [plaintiffs] presence on the National Do Not Call Registry';

    /**
     * 'IDNCL' piece
     */
    const IDNCL = ' without maintaining internal do not call procedures as required by law';

    /**
     * 'TIAA' piece
     */
    const TIAA = '[also], the text messages violated the Utah Truth In Advertising Act.';

    /**
     * Singular words for replacing plaintiffs blocks ([plaintiffs])
     */
    const SINGULAR_PLAINTIFF = [
        'Plaintiff',
        'brings',
        'Plaintiff\'s',
        'Plaintiff\'s',
        'number',
        'Plaintiff',
        'Plaintiff\'s',
    ];

    /**
     * Plural words for replacing plaintiffs blocks ([plaintiffs])
     */
    const PLURAL_PLAINTIFFS = [
        'Plaintiffs',
        'bring',
        'their',
        'Plaintiffs\'',
        'numbers',
        'Plaintiffs',
        'their',
    ];

    /**
     * Singular word for replacing defendants blocks ([defendants])
     */
    const SINGULAR_DEFENDANT = [
        '("Defendant")',
        'its',
        'Defendant'
    ];

    /**
     * Plural word for replacing defendants blocks ([defendants])
     */
    const PLURAL_DEFENDANT = [
        '("Defendants")',
        'their',
        'Defendants'
    ];

    /**
     * Wrapper for remark
     */
    const WRAPPER = '("%s")';

    /**
     * @var bool
     */
    public $DNCR = false;

    /**
     * @var bool
     */
    public $IDNCL = false;

    /**
     * @var bool
     */
    public $TIAA = false;

    /**
     *  Exploded plaintiff string
     *
     * @var array
     */
    public $plaintiff = [];

    /**
     * Exploded defendants string
     *
     * @var array
     */
    public $defendants = [];

    /**
     * Count of plaintiff
     *
     * @var int
     */
    public $plaintiffCount = 0;

    /**
     * Count of defendants
     *
     * @var int
     */
    public $defendantsCount = 0;

    /**
     * Get plural or singular mark
     *
     * @var bool
     */
    private $plaintiffPlural = false;

    /**
     * Get plural or singular mark
     *
     * @var bool
     */
    private $defendantsPlural = false;

    /**
     * Finished parsed text
     *
     * @var
     */
    private $parsedText;

    /**
     * PolicyText constructor.
     * @param array $params
     */
    public function __construct(array $params)
    {
        $plaintiffsForm = $params['plaintiffs'];
        $defendantsForm = $params['defendants'];
        $this->resolveCheckboxes($params)
            ->setPlaintiffs($plaintiffsForm)
            ->setDefendants($defendantsForm)
            ->setPlaintiffsCount()
            ->setDefendantsCount()
            ->checkPlaintiffsOnPlural()
            ->checkDefendantsOnPlural();

        $this->parse();
    }

    /**
     * @return mixed
     */
    public function getParsedText()
    {
        return $this->parsedText;
    }

    /**
     *  Run parse proccess
     */
    private function parse(): void
    {
        $this->generateFullText()
            ->replaceBlocks('plaintiffs')
            ->replaceBlocks('defendants')
            ->buildPlaintiffNames()
            ->buildDefendantsNames();
    }

    /**
     * Get bool values from checkboxes
     *
     * @param array $params
     * @return $this
     */
    private function resolveCheckboxes(array $params): self
    {
        if (isset($params['DNCR'])) {
            $this->DNCR = $params['DNCR'] === 'on' ? true : false;
        }

        if (isset($params['IDNCL'])) {
            $this->IDNCL = $params['IDNCL'] === 'on' ? true : false;
        }

        if (isset($params['TIAA'])) {
            $this->TIAA = $params['TIAA'] === 'on' ? true : false;
        }
        return $this;
    }

    /**
     * Get count of plaintiffs
     *
     * @return $this
     */
    private function setPlaintiffsCount(): self
    {
        $this->plaintiffCount = count($this->plaintiff);
        return $this;
    }

    /**
     * Get count of defendants
     *
     * @return $this
     */
    private function setDefendantsCount(): self
    {
        $this->defendantsCount = count($this->defendants);
        return $this;
    }

    /**
     * Check plaintiff for plural
     *
     * @return $this
     */
    private function checkPlaintiffsOnPlural(): self
    {
        $this->plaintiffPlural = $this->plaintiffCount > 1;
        return $this;
    }

    /**
     * Check defendants for plural
     *
     * @return $this
     */
    private function checkDefendantsOnPlural(): self
    {
        $this->defendantsPlural = $this->defendantsCount > 1;
        return $this;
    }

    /**
     * Explode plaintiffs
     *
     * @param string $plaintiffs
     * @return $this
     */
    private function setPlaintiffs(string $plaintiffs): self
    {
        $this->plaintiff = array_filter(explode(';', $plaintiffs));
        return $this;
    }

    /**
     * Explode defendants
     *
     * @param string $defendants
     * @return $this
     */
    private function setDefendants(string $defendants): self
    {
        $this->defendants = array_filter(explode(';', $defendants));
        return $this;
    }

    /**
     * Generate text with blocks by checkboxes
     *
     * @return $this
     */
    private function generateFullText(): self
    {
        $both = $this->DNCR && $this->IDNCL;
        $tcpa = $this->DNCR || $this->IDNCL;
        $and = $both ? ', ' . self::AND : '';
        $this->parsedText = self::TEXT;

        $text = $tcpa ? self::TCPA : '';
        $this->parsedText = str_replace('[TCPA]', $text, $this->parsedText);

        $text = $this->DNCR ? self::DNCR : '';
        $this->parsedText = str_replace('[DNCR]', $text, $this->parsedText);

        $text = $this->IDNCL ? $and . self::IDNCL : '';
        $this->parsedText = str_replace('[IDNCL]', $text, $this->parsedText);

        $text = $this->TIAA ? self::TIAA : '.';
        $this->parsedText = str_replace('[TIAA]', $text, $this->parsedText);

        $text = $this->TIAA && $both ? '. Lastly' : '. Also';
        $this->parsedText = str_replace('[also]', $text, $this->parsedText);

        return $this;
    }

    /**
     * Replace all block on plural or singular words
     *
     * @param string $subject
     * @return $this
     */
    private function replaceBlocks(string $subject): self
    {
        $words = [];
        switch ($subject) {
            case 'plaintiffs':
                $words = $this->plaintiffPlural ? self::PLURAL_PLAINTIFFS : self::SINGULAR_PLAINTIFF;
                $this->wrapFirstWordIfNeeded($words);
                break;
            case 'defendants':
                $words = $this->defendantsPlural ? self::PLURAL_DEFENDANT: self::SINGULAR_DEFENDANT;
                break;
        }

        foreach ($words as $word) {
            $this->parsedText = preg_replace("/\[$subject]/", $word, $this->parsedText, 1);
        }
        return $this;
    }

    /**
     * @param array $words
     */
    private function wrapFirstWordIfNeeded(array &$words): void
    {
        if ($this->plaintiffCount <= 5) {
            $words[0] = sprintf(self::WRAPPER, $words[0]);
        }
    }

    /**
     * Replace name block to generated names
     *
     * @return $this
     */
    private function buildPlaintiffNames(): self
    {
        $count = $this->plaintiffCount;

        if ($count > 5) {
            $count = 0;
        }

        if ($count > 2) {
            $count = 3;
        }

        $names = $this->nameBuilder($count, $this->plaintiff);
        $this->parsedText = str_replace('[name.plaintiffs]', $names, $this->parsedText);
        return $this;
    }

    /**
     * Replace name block to generated names
     *
     * @return $this
     */
    private function buildDefendantsNames(): self
    {
        $count = $this->defendantsCount;

        if ($count > 2) {
            $count = 3;
        }

        $names = $this->nameBuilder($count, $this->defendants);
        $this->parsedText = str_replace('[name.defendants]', $names, $this->parsedText);
        return $this;
    }

    /**
     * Generate names with/without 'and'
     *
     * @param int $count
     * @param array $names
     * @return string
     */
    private function nameBuilder(int $count, array $names): string
    {
        if ($count === 0) {
            return '';
        } else {
            $firstNames = array_slice($names, 0, $count);
            $lastName = $firstNames[$count - 1];
            $glue = '';
            if ($count > 1) {
                $firstNames[$count - 1] = self::AND . ' ' . $lastName;
                $glue = $count > 2 ? ', ' : ' ';
            }
            return implode($glue, $firstNames);
        }
    }
}
