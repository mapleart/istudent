{**
 * Главная
 *}
{extends 'layouts/layout.base.tpl'}

{block 'layout_page_title'}
    Управление категориями
{/block}

{block 'layout_content'}

    <form action="{router page='admin'}maps/add/" method="post" enctype="multipart/form-data">
        <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}" />
        <input type="hidden" name="id" value="{$_aRequest.id}" />

        <input type="hidden" name="lat" value="{$_aRequest.lat}" id="inputMap-ValueLat">
        <input type="hidden" name="lng" value="{$_aRequest.lng}" id="inputMap-ValueLng">

        <div class="form-group">
            <label>Тип</label>
            <select name="target_type" class="form-control">
                <option {if $_aRequest.target_type == ModuleMaps::TYPE_GYM}selected{/if} value="{ModuleMaps::TYPE_GYM}">Спортзалы</option>
                <option {if $_aRequest.target_type == ModuleMaps::TYPE_CORPUS}selected{/if} value="{ModuleMaps::TYPE_CORPUS}">Корпуса</option>
                <option {if $_aRequest.target_type == ModuleMaps::TYPE_DANCE}selected{/if} value="{ModuleMaps::TYPE_DANCE}">Танцевальные студии</option>
                <option {if $_aRequest.target_type == ModuleMaps::TYPE_VOCAL}selected{/if} value="{ModuleMaps::TYPE_VOCAL}">Вокальные студии</option>
                <option {if $_aRequest.target_type == ModuleMaps::TYPE_SOCIAL}selected{/if} value="{ModuleMaps::TYPE_SOCIAL}">Общественная деятельность</option>
                <option {if $_aRequest.target_type == ModuleMaps::TYPE_MEDICAL}selected{/if} value="{ModuleMaps::TYPE_MEDICAL}">Мед. центры</option>
            </select>
        </div>

        <div class="form-group">
            <label>ID цели</label>
            <input type="text" name="target_id" value="{$_aRequest.target_id}" class="form-control" placeholder="" />
        </div>

        <div class="form-group">
            <label>Название обьекта</label>
            <input type="text" name="title" value="{$_aRequest.title}" class="form-control" placeholder="Спортзал" />
        </div>
        {*<div class="form-group">
            <label>{$aLang.plugin.sfishing.form.category}</label>
            <select name="category_id" class="form-control">
                {foreach $aCategories as $key=>$sCategory}
                    <option value="{$key+1}" {if $_aRequest.category_id == ($key+1)}selected="selected"{/if}>{$aLang.plugin.sfishing.form.categories[$sCategory]}</option>
                {/foreach}
            </select>
        </div>*}
        <div class="form-group">
            <label>Описание</label>
            <textarea name="description"  class="form-control" placeholder="">{$_aRequest.description}</textarea>
        </div>



        <div class="form-group">
            {if $_aRequest.photo}
                <img src="{$_aRequest.photo}" style="max-width: 200px">
                <div class="checkbox">
                    <label><input type="checkbox" name="delete_photo" value="1">
                        Удалить
                    </label>
                </div>
                <br>
            {/if}

            <label for="formInputFile">Фото обьекта</label>
            <input type="file" name="photo" id="formInputFile">
            <p class="help-block">Загрузите фото</p>
        </div>

        <div class="form-group">
            <label>Адрес объекта</label>
            <input type="text" name="address" value="{$_aRequest.address}" class="form-control" id="inputMap-ValueAddress"  />

            <div class="alert alert-info" id="inputMap-ValueHelp" role="alert" style="display: none; margin-top: 5px;"></div>
        </div>



        <script >
            function initMap() {
                ls.goolemap.createInputMap();
            }
        </script>

        <div class="form-group">
            <div id="googleMap-input"></div>
        </div>


        <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key={$oConfig->Get('plugin.gmappost.google_api_key')}&libraries=places&callback=initMap" async defer></script>

        <hr>

        <h2>Контакты</h2>
        <div class="form-group">
            <label>Телефон</label>
            <input type="text" name="phone" value="{$_aRequest.phone}" class="form-control" />
        </div>

        <input type="submit" value="Сохранить" name="submit_add" class="btn  btn-primary" />
    </form>

    <style>
        #googleMap-input {
            width: 100%;
            height: 500px;
        }
    </style>
{/block}
