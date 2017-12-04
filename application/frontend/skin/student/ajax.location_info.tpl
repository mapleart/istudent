{if $oMap}
    <div class="marker-info">

            <div class="marker-description">
                {$oMap->getDescription()}
            </div>

            <div class="marker-description" >
                <i class="ion-ios-location"></i> {$oMap->getAddress()}
            </div>

        <br>

        <div >
            <p class="mb-0" style="color: #a3a3a3;">Телефон для записи:</p>
            <h3 class="marker-phone" style="font-weight: bold;">{$oMap->getPhone()}</h3>
        </div>

    </div>


{/if}
<style>


</style>