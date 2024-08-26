<?php 
/*
 * This file is part of WHATPANEL.
 *
 * @package     WHAT PANEL – Web Hosting Application Terminal Panel.
 * @copyright   2023-2024 Version Next Technologies and MadPopo. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://www.version-next.com
 */

namespace Twilio\TaskRouter;

/**
 * Twilio TaskRouter Workflow Rule
 *
 * @author Justin Witz <jwitz@twilio.com>
 * @license  http://creativecommons.org/licenses/MIT/ MIT
 */
class WorkflowRule implements \JsonSerializable {
    public $expression;
    public $friendly_name;
    public $targets;

    public function __construct(string $expression, array $targets, string $friendly_name = null) {
        $this->expression = $expression;
        $this->targets = $targets;
        $this->friendly_name = $friendly_name;
    }

    public function jsonSerialize(): array {
        $json = [];
        $json['expression'] = $this->expression;
        $json['targets'] = $this->targets;
        if ($this->friendly_name !== null) {
            $json['friendly_name'] = $this->friendly_name;
        }
        return $json;
    }
}
