<?php

namespace Framework\Twig;

class FormExtenxion extends \Twig_Extension
{

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new \Twig_SimpleFunction('field', [$this, 'field'], [
                'is_safe' => ['html'],
                'needs_context' => true
            ])
        ];
    }

    /**
     * @param array $context
     * @param string $key
     * @param $value
     * @param null|string $label
     * @param array $options
     * @return string
     */
    public function field(array $context, string $key, $value, ?string $label = null, array $options = [])
    {
        $type = $options['type']?? 'text' ;

        $value = $this->convertValue($value);

        $class = 'form-group';

        $attributes = [
            'class' => 'form-control ' . ($options['class']?? ''),
            'name' => $key,
            'id' => $key
        ];
        $errors = $this->getErrorHTML($context, $key);
        if ($errors) {
            $class .= ' has-danger';
            $attributes['class'] .= ' form-control-danger';
        }

        if ($type === 'textarea') {
            $input = $this->textarea($value, $attributes);
        } else {
            $input = $this->input($value, $attributes);
        };

        return "<div class=\"".$class ."\">
                    <label for=\"{$key}\">{$label}</label>
                    {$input}
                    {$errors}
                </div>";
    }

    private function getErrorHTML($context, $key)
    {
        $errors = $context['errors'][$key]?? false;
        if ($errors) {
            return "<small class=\"form-text text-muted\">{$errors}</small>";
        } else {
            return "";
        }
    }

    private function input(?string $value, array $attributes): string
    {
        return "<input type=\"text\"". $this->TransformAtributes($attributes). "value=\"{$value}\">";
    }

    private function textarea(?string $value, array $attributes): string
    {
        return " <textarea ". $this->TransformAtributes($attributes). ">{$value}</textarea>";
    }

    private function TransformAtributes(array $attributes)
    {
        return implode(' ', array_map(function ($key, $value) {
            return "$key=\"$value\"";
        }, array_keys($attributes), $attributes));
    }

    private function convertValue($value): string
    {
        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d H:i:s');
        }
        return (string)$value;
    }
}
