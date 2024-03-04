# QuickEditAccordion Widget

## Description

`QuickEditAccordion` is a Yii2 widget designed to display data in an accordion layout with inline editing capabilities. It supports both table and list views and uses AJAX for asynchronous data updates.

## Installation

To install the `QuickEditAccordion` widget, add it to your Yii2 project by including it in your composer.json file or cloning the repository into your project directory.


## Configuration

The widget provides several customizable properties:

- `type`: Display type (`'table'` or `'list'`). Default is `'table'`.
- `action`: URL to which form data will be sent for saving.
- `items`: Array of items to be displayed in the accordion.
- Additional options for customizing appearance and behavior: `options`, `accordionOptions`, `listStyle`, `labelStyle`, `valueStyle`.

## Example Usage

```php
echo QuickEditAccordion::widget([
    'type' => 'list',
    'action' => Url::to(['controller/action']),
    'items' => [
        // Your items array
    ],
]);
```

## JavaScript Functionality

The widget includes JavaScript code to enable inline editing functionality. Clicking an element marked with the `.editable` class turns it into a text field, allowing the user to modify its value. The changes are saved asynchronously via AJAX.

### Example of a Controller to Handle the AJAX Request

```php
public function actionSave() {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $index = Yii::$app->request->post('index');
    $attribute = Yii::$app->request->post('attribute');
    $newValue = Yii::$app->request->post('newValue');

    // Logic for processing changes saving

    return ['success' => true, 'newValue' => $newValue];
}
```