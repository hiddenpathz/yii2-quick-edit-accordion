<?php

namespace hiddenpathz\QuickEditAccordion;

use hiddenpathz\QuickEditAccordion\assets\QuickEditAccordionAsset;
use Throwable;
use yii\base\Widget;
use yii\bootstrap\Collapse;
use yii\helpers\Html;

/**
 * QuickEditAccordion Widget
 *
 * This widget renders an accordion that can switch between a table view and a list view.
 * Each item within the accordion can be edited inline.
 */
class QuickEditAccordion extends Widget
{
    /**
     * @var string The display type of the accordion content ('table' or 'list').
     */
    public $type = 'table';

    /**
     * @var string The URL to which the form will be submitted.
     */
    public $action = '/';

    /**
     * @var array The items to be displayed within the accordion.
     */
    public $items = [];

    /**
     * @var array HTML options for the accordion container.
     */
    public $options = [];

    /**
     * @var array Additional options for the accordion.
     */
    public $accordionOptions = [];

    /**
     * @var array CSS styles for the list view.
     */
    public $listStyle = [];

    /**
     * @var array CSS styles for the labels.
     */
    public $labelStyle = [];

    /**
     * @var array CSS styles for the values.
     */
    public $valueStyle = [];

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();

        Html::addCssClass($this->options, ['widget' => 'panel-group quick-edit-accordion']);

        $this->options['data-action'] = $this->action;

        QuickEditAccordionAsset::register($this->getView());
    }

    /**
     * Executes the widget.
     * @return string the widget's HTML output
     * @throws Throwable
     */
    public function run(): string
    {
        return implode("\n", [
                Html::beginTag('div', $this->options),
                $this->renderItems(),
                Html::endTag('div')
            ]) . "\n";
    }

    /**
     * Renders the accordion items.
     * @return string Rendered items as a string
     * @throws Throwable
     */
    private function renderItems(): string
    {
        $result = [];

        foreach ($this->items as  $item) {

            $isOpen = array_key_exists('openDefault', $item) === true && $item['openDefault'] === true ? 'in' : '';

            $result[] = Collapse::widget([
                'items' => [
                    [
                        'label' => $item['title'],
                        'content' => $this->renderElem($item['list'], $item['id']),
                        'contentOptions' => ['class' => $isOpen],
                        'options' => []
                    ]
                ],
                'options' => array_merge(
                    $this->accordionOptions,
                    ['class' => 'panel-default']
                ),
            ]);
        }


        return implode("\n", $result);
    }

    /**
     * Renders accordion content based on the specified type.
     * @param array $item The item to be rendered
     * @param int $key The key of the item
     * @return string Rendered content as a string
     */
    private function renderElem(array $item, int $key): string
    {
        if ($this->type === 'table') {
            return $this->renderElemAsTable($item, $key);
        }

        if ($this->type === 'list') {
            return $this->renderElemAsList($item, $key);
        }

        return '';
    }

    /**
     * Renders elements as a table.
     * This method generates a table layout for displaying items.
     *
     * @param array $item The item data to be rendered in table format.
     * @param int $key The unique key of the item, used for identifying editable fields.
     * @return string The rendered table as a string.
     */
    private function renderElemAsTable(array $item, int $key): string
    {
        $rows = "";

        foreach ($item as $elem) {
            $rows .= Html::tag('tr',
                Html::tag('td', $this->printLabel($elem)) . Html::tag('td', $this->printValue($elem, $key))
            );
        }

        $options = [
            'class' => 'detail-view-table'

        ];

        $style = empty($this->listStyle) ? [] : [
            'style' => implode(';', $this->listStyle)
        ];

        return Html::tag('table', $rows, array_merge($options, $style));
    }

    /**
     * Renders elements as a list.
     * This method generates a list layout for displaying items.
     *
     * @param array $item The item data to be rendered in list format.
     * @param int $key The unique key of the item, used for data attributes and identifying editable fields.
     * @return string The rendered list as a string.
     */
    private function renderElemAsList(array $item, int $key): string
    {
        $rows = "";

        foreach ($item as $elem) {

            $content = Html::tag('div',
                $this->printLabel($elem) . ' : ' . $this->printValue($elem, $key),
                ['class' => 'elem-row']
            );

            $rows .= Html::tag('li', $content);
        }

        $options = [
            'class' => 'detail-view-list'

        ];

        $style = empty($this->listStyle) ? [] : [
            'style' => implode(';', $this->listStyle)
        ];

        return Html::tag('ul', $rows, array_merge($options, $style));
    }

    /**
     * Prints the label for an item.
     * This method generates the HTML for an item's label, applying styles as specified.
     *
     * @param array $data The data array containing the label and its associated properties.
     * @return string The rendered label as HTML string.
     */
    private function printLabel(array $data): string
    {
        $label = array_key_exists('label', $data) ? $data['label'] : $data['key'];

        $options = [
            'class' => 'label-elem-accordion',

        ];

        $style = empty($this->labelStyle) ? [] :[
            'style' => implode(';', $this->labelStyle)
        ];

        return Html::tag('div', Html::encode($label ?? ''), array_merge($options, $style));
    }

    /**
     * Prints the value for an item.
     * This method generates the HTML for an item's value, making it editable if specified.
     *
     * @param array $data The data array containing the value and its properties.
     * @param int $key The unique key of the item, used for data attributes.
     * @return string The rendered value as HTML string, with data attributes and styles applied.
     */
    private function printValue(array $data, int $key): string
    {
        $isEditable = 'editable cursor-pointer';

        if (array_key_exists('editable', $data) === true && $data['editable'] === false) {
            $isEditable = '';
        }

        $options = [
            'class' => $isEditable . ' value-elem-accordion',
            'data-index' => $key,
            'data-attribute' => $data['key'],
        ];

        $style = empty($this->valueStyle) ? [] : [
            'style' => implode(';', $this->valueStyle)
        ];


        return Html::tag('div', Html::encode($data['value'] ?? ''), array_merge($options, $style));
    }



}