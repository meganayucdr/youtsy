<?php

namespace App\Fields;

use Illuminate\Contracts\Support\Arrayable;

class Select2Ajax implements Arrayable
{
    protected $model_class;
    protected $name;
    protected $url;
    protected $value;
    protected $label;
    protected $data_property;
    protected $current_page_property;
    protected $last_page_property;
    protected $id_property;
    protected $text_property;
    protected $note_property;
    protected $minimum_input_length;
    protected $tags;
    protected $delay_ajax;
    protected $required;
    protected $disabled;
    protected $readonly;
    protected $options;

    public function __construct($model_class, $name = null, $url = null, $value = null, $label = null, $id_property = null, $text_property = null, $note_property = null, $minimum_input_length = null, $tags = false, $delay_ajax = 250, $required = false, $disabled = false, $readonly = false, $options = null)
    {
        $singular = snake_case(class_basename($model_class));
        $plural = str_plural($singular);

        $this->model_class = $model_class;
        $this->name = $name ? : (new $model_class)->getForeignKey();
        $this->url = $url ? : route("api.$plural.index");
        $this->value = old($this->name, $value ?? request()->{$this->name});
        $this->label = $label ? : ucwords(__("$plural.singular"));
        $this->id_property = $id_property ? : (new $model_class)->getKeyName();
        $this->text_property = $text_property ? : 'text';
        $this->note_property = $note_property;
        $this->minimum_input_length = $minimum_input_length;
        $this->tags = $tags;
        $this->delay_ajax = $delay_ajax;
        $this->required = $required;
        $this->disabled = $disabled;
        $this->readonly = $readonly;
        $this->options = $options;

        return $this;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_filter([
            'field' => 'select2-ajax',
            'name' => $this->name,
            'url' => $this->url,
            'value' => $this->value,
            'label' => $this->label,
            'data-property' => $this->data_property,
            'current-page-property' => $this->current_page_property,
            'last-page-property' => $this->last_page_property,
            'id-property' => $this->id_property,
            'text-property' => $this->text_property,
            'note-property' => $this->note_property,
            'minimum-input-length' => $this->minimum_input_length,
            'tags' => $this->tags,
            'delay-ajax' => $this->delay_ajax,
            'required' => $this->required,
            'disabled' => $this->disabled,
            'readonly' => $this->readonly,
            'options' => $this->options ? : $this->model_class::where((new $this->model_class)->qualifyColumn($this->id_property), $this->value)->get()->map(function ($model) {
                return [ 'id' => $model->{$this->id_property}, 'text' => data_get($model, $this->text_property).($this->note_property ? " (".data_get($model, $this->note_property).")" : "") ];
            })->prepend([ 'id' => '', 'text' => '-' ])->toArray(),
        ]);
    }

    /**
     * @param mixed $name
     * @return Select2Ajax
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param mixed $url
     * @return Select2Ajax
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param mixed $value
     * @return Select2Ajax
     */
    public function setValue($value)
    {
        $this->value = old($this->name, $value ?? request()->{$this->name});
        return $this;
    }


    /**
     * @param mixed $label
     * @return Select2Ajax
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @param mixed $data_property
     * @return Select2Ajax
     */
    public function setDataProperty($data_property)
    {
        $this->data_property = $data_property;
        return $this;
    }

    /**
     * @param mixed $current_page_property
     * @return Select2Ajax
     */
    public function setCurrentPageProperty($current_page_property)
    {
        $this->current_page_property = $current_page_property;
        return $this;
    }

    /**
     * @param mixed $last_page_property
     * @return Select2Ajax
     */
    public function setLastPageProperty($last_page_property)
    {
        $this->last_page_property = $last_page_property;
        return $this;
    }

    /**
     * @param mixed $id_property
     * @return Select2Ajax
     */
    public function setIdProperty($id_property)
    {
        $this->id_property = $id_property;
        return $this;
    }

    /**
     * @param mixed $text_property
     * @return Select2Ajax
     */
    public function setTextProperty($text_property)
    {
        $this->text_property = $text_property;
        return $this;
    }

    /**
     * @param mixed $note_property
     * @return Select2Ajax
     */
    public function setNoteProperty($note_property)
    {
        $this->note_property = $note_property;
        return $this;
    }

    /**
     * @param mixed $minimum_input_length
     * @return Select2Ajax
     */
    public function setMinimumInputLength($minimum_input_length)
    {
        $this->minimum_input_length = $minimum_input_length;
        return $this;
    }

    /**
     * @param bool $tags
     * @return Select2Ajax
     */
    public function setTags($tags = true)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @param $delay_ajax
     * @return Select2Ajax
     */
    public function setDelayAjax($delay_ajax)
    {
        $this->delay_ajax = $delay_ajax;
        return $this;
    }

    /**
     * @param mixed $required
     * @return Select2Ajax
     */
    public function setRequired($required = true)
    {
        $this->required = $required;
        return $this;
    }

    /**
     * @param mixed $disabled
     * @return Select2Ajax
     */
    public function setDisabled($disabled = true)
    {
        $this->disabled = $disabled;
        return $this;
    }

    /**
     * @param mixed $readonly
     * @return Select2Ajax
     */
    public function setReadonly($readonly = true)
    {
        $this->readonly = $readonly;
        return $this;
    }

    /**
     * @param mixed $options
     * @return Select2Ajax
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function setValueFromModel($model)
    {
        return $this->setValue( $model ? data_get($model, $this->name) : null);
    }
}
