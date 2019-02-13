<?php

namespace Derralf\Elements\CallToAction\Element;


use DNADesign\Elemental\Models\BaseElement;
use gorriecoe\LinkField\LinkField;
use gorriecoe\Link\Models\Link;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\DropdownField;

class ElementCallToAction extends BaseElement
{
    public function getType()
    {
        return self::$singular_name;
    }

    private static $icon = 'font-icon-right-dir';

    private static $table_name = 'ElementCallToAction';

    private static $singular_name = 'Call to Action';

    private static $plural_name = 'Call to Action';

    private static $db = [
        'HTML'                    => 'HTMLText',
        'Color'                   => 'Varchar(255)',
    ];

    private static $has_one = [
        'ReadMoreLink' => Link::Class

    ];

    private static $inline_editable = false;

    private static $colors = [];

    public function fieldLabels($includerelations = true)
    {
        $labels = parent::fieldLabels($includerelations);

        $labels['Color'] = _t(__CLASS__ . '.ColorLabel', 'Color');
        $labels['ReadMoreLink'] = _t(__CLASS__ . '.ReadMoreLinkLabel', 'ReadMoreLink');
        $labels['Title'] = _t(__CLASS__ . '.TitleLabel', 'Title');
        $labels['Sort'] = _t(__CLASS__ . '.SortLabel', 'Sort');

        return $labels;
    }

    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function ($fields) {

            // Style: Description for default style (describes Layout thats used, when no special style is selected)
            $Style = $fields->dataFieldByName('Style');
            $StyleDefaultDescription = $this->owner->config()->get('styles_default_description', Config::UNINHERITED);
            if ($Style && $StyleDefaultDescription) {
                $Style->setDescription($StyleDefaultDescription);
            }

            // ReadMoreLink: use Linkfield
            $ReadMoreLink = LinkField::create('ReadMoreLink', 'Link', $this);
            $fields->replaceField('ReadMoreLinkID', $ReadMoreLink);


            $colors = $this->config()->get('colors');
            if ($colors && count($colors) > 0) {
                $colorDropdown = DropdownField::create('Color', $this->fieldLabel('Color'), $colors);

                $fields->insertBefore($colorDropdown, 'HTML');

                $colorDropdown->setEmptyString(_t(__CLASS__.'.CUSTOM_COLORS', 'Select a color...'));
            } else {
                $fields->removeByName('Color');
            }



        });
        $fields = parent::getCMSFields();
        return $fields;
    }

    public function ReadmoreLinkClass() {
        return $this->config()->get('readmore_link_class');
    }




}
