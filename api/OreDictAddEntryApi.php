<?php

class OreDictAddEntryApi extends ApiBase {
    public function __construct($query, $moduleName) {
        parent::__construct($query, $moduleName, 'od');
    }

    public function getAllowedParams() {
        return array(
            'mod' => array(
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => true,
            ),
            'tag' => array(
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => true,
            ),
            'item' => array(
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_REQUIRED => true,
            ),
            'params' => array(
                ApiBase::PARAM_TYPE => 'string',
                ApiBase::PARAM_DFLT => '',
            ),
            'flags' => array(
                ApiBase::PARAM_TYPE => 'integer',
                ApiBase::PARAM_DFLT => OreDict::FLAG_DEFAULT,
            ),
            'token' => null,
        );
    }

    public function needsToken() {
        return 'csrf';
    }

    public function getTokenSalt() {
        return '';
    }

    public function mustBePosted() {
        return true;
    }

    public function isWriteMode() {
        return true;
    }

    public function getParamDescription() {
        return array(
            'mod' => 'The mod abbreviation for the new entry',
            'tag' => 'The tag name for the new entry',
            'item' => 'The item name for the new entry',
            'params' => 'The grid parameters for the new entry',
            'flags' => 'The flags for the new entry',
            'token' => 'The edit token',
        );
    }

    public function getDescription() {
        return 'Creates a new OreDict entry.';
    }

    public function getExamples() {
        return array(
            'api.php?action=neworedict&odmod=V&odtag=logWood&oditem=Oak Wood Log',
        );
    }

    public function execute() {
        $mod = $this->getParameter('mod');
        $item = $this->getParameter('item');
        $tag = $this->getParameter('tag');
        $params = $this->getParameter('params');
        $flags = $this->getParameter('flags');

        if (!OreDict::entryExists($item, $tag, $mod)) {
            $result = OreDict::addEntry($mod, $item, $tag, $this->getUser(), $params, $flags);
            $ret = array('result' => $result);
            $this->getResult()->addValue('edit', 'neworedict', $ret);
        } else {
            $this->dieUsage('Entry already exists', 'entryexists');
        }
    }
}