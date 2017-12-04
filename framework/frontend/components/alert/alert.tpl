{**
 * Уведомления
 *
 * @param string  $title          Заголовок
 * @param mixed   $text           Массив либо строка с текстом уведомления. Массив должен быть в формате: `[ [ title, msg ], ... ]`
 * @param bool    $visible        Показывать или нет уведомление
 * @param bool    $dismissible    Показывать или нет кнопку закрытия
 * @param string  $mods="success" Список модификторов основного блока (через пробел)
 * @param string  $classes        Список классов основного блока (через пробел)
 * @param array   $attributes     Список атрибутов основного блока
 *}

{* Название компонента *}
{$component = 'alert'}
{component_define_params params=[ 'title', 'text', 'visible', 'dismissible', 'close', 'mods', 'classes', 'attributes' ]}

{* Дефолтные значения *}
{$uid = "{$component}{mt_rand()}"}
{$visible = $visible|default:true}

{$dismissible = ( $close ) ? $close : $dismissible}
{if $dismissible}
    {$mods = "$mods dismissible"}
{/if}

{block 'alert_options'}{/block}

{* Уведомление *}
<div class="{$component} {cmods name=$component mods=$mods} {$classes} js-alert" role="alert" {if ! $visible}hidden{/if} {cattr list=$attributes}>
    {* Заголовок *}
    {if $title}
        <h4 class="{$component}-title">{$title}</h4>
    {/if}

    {* Контент *}
    {if $text}
            {block 'alert_body'}
                {if is_array( $text )}
                        {foreach $text as $alert}

                                {if $alert.title}
                                    <strong>{$alert.title}</strong>:
                                {/if}

                                {$alert.msg}
                            </>
                        {/foreach}
                {else}
                    {$text}
                {/if}
            {/block}
    {/if}
</div>
