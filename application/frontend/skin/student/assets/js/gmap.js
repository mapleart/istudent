/***
 *
 */
var ls = ls || {};

ls.goolemap =( function ($) {

    var ns=this;
    var map;
    var claster;
    var markers = [];
    var marker = '';

    var pageMap = null;
    var markersCurrent = {};

    this.default = {
        defaultCenter: {
            lat: '57.14499447',
            lng: '65.58022499'
        },
        inputId: 'googleMap-input',
        viewId: 'googleMap-view',
        areas: {
            premium_list :'.js-premiumList',
            city_name: '.js-currentCity-name'
        },
        elInputValue: {
            lat: '#inputMap-ValueLat',
            lng: '#inputMap-ValueLng',
            help: '#inputMap-ValueHelp',
            address: '#inputMap-ValueAddress',
            default_category: '#mapInput-categoryDefault'
        },
        icons_url: PATH_SKIN + '/images/marker/',

        tab_selector: {
            tab: '.map-sidebarTab',
            marker: '#jsTab-marker',
            list: '#jsTab-index'
        },
        filter: {
            input_search: '.js-mapSearch-input'
        },
        zoom: {
            init: 11,
            city: 11,
            home: 11
        },
        target_type: 1
    };

    /**
     * Создает карту!!
     **/
    this.createMap=function (id, options) {
        var mapTypeIds = [];
        var mapTypes = [];

        var mapConfigDefault = {
            zoom: ns.default.zoom.init || 5,
            center: new google.maps.LatLng( ns.default.defaultCenter.lat || 0, ns.default.defaultCenter.lng || 0),
            //mapTypeControl: false, // map/sputnik
            streetViewControl: false, // Панарамы улиц
            panControl: true,  // зумм
            scrollwheel: true, // зумм колесом
            mapTypeControlOptions: {
                mapTypeIds: []
            },
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        mapConfig = $.extend(mapConfigDefault, options);
        console.info(mapConfig);

        var elementMap = document.getElementById(id);
       // var elementInput = document.getElementById(config.elementInputId);

        if (elementMap) {

            map = new google.maps.Map(elementMap, mapConfig);


            return map;
        }

        return false;
    };


    /**
     * Обработка карты
     */
    this.createItemMap = function($lat, $lng) {
        ns.map = ls.goolemap.createMap(ns.default.inputId, {
            scrollwheel: false
        });




        if (!(ns.marker instanceof google.maps.Marker) && $lat != 0 && $lng != 0) {
            // Новый маркер
            var icons = {
                default: {
                    icon: ns.default.icons_url + 'marker.png'
                }
            };
            ns.marker = new google.maps.Marker({
                map: ns.map,
                position: new google.maps.LatLng($lat, $lng),
                icon: icons.default.icon,
                draggable: false
            });

            ns.map.setZoom(ns.default.zoom.home);
            ns.map.setCenter(ns.marker.getPosition());
        }

    };

    this.slideToggleMap=function(){
        $('#'+ns.default.inputId).slideToggle('fast', function(){
            google.maps.event.trigger(ns.map, "resize"); // resize map
            ns.map.setCenter(ns.marker.getPosition()); // set the center
        }); // slide it down
    };

    /**
     * Обработка карты
     */
    this.createInputMap = function() {
        ns.map = ls.goolemap.createMap(ns.default.inputId);

        // Событие нажатия на карту и добавление маркера
        google.maps.event.addListener(map, 'click', function (event) {
            ns.stickyMarker(event.latLng);
        });

        if (!(marker instanceof google.maps.Marker) && $(ns.default.elInputValue.lat).val() != 0 && $(ns.default.elInputValue.lng).val() != 0) {
            ns.stickyMarker(new google.maps.LatLng($(ns.default.elInputValue.lat).val(), $(ns.default.elInputValue.lng).val()));
        }

    };

    this.stickyMarker = function (location) {
        // Удаляем маркер который не уже есть
        if (marker instanceof google.maps.Marker) {
            marker.setMap(null);
        }

        // Новый маркер
        marker = new google.maps.Marker({
            map: ns.map,
            position: location,
            draggable: true
        });

        // Перетаскивание и обновление данных о точке
        google.maps.event.addListener(marker, 'dragend', function (event) {
            ns.saveLocationValue(event.latLng);
            ns.map.panTo(event.latLng);
        });

        ns.saveLocationValue(location);
        map.panTo(location);
    };

    this.saveLocationValue = function (location) {
        ns.convertLocationToAddress(location);
        $(ns.default.elInputValue.lat).val(location.lat());
        $(ns.default.elInputValue.lng).val(location.lng());
    };

    this.convertLocationToAddress = function (location) {
        var geocoder= new google.maps.Geocoder();
        geocoder.geocode({
            latLng: location
        }, function(responses) {
            if (responses && responses.length > 0) {
                ns.setHelpAddress(responses[0].formatted_address);
            } else {
               return ns.setHelpAddress(false);
            }
        });
    };

    this.setHelpAddress = function (text) {
        if(text){
            var $setAddresValue = '<p><button class="btn btn-info js-setValue">Вставить</button></p>';
            $(ns.default.elInputValue.help).html(text+$setAddresValue).show();
            $(ns.default.elInputValue.help).find('.js-setValue').click(function () {
                $(ns.default.elInputValue.address).val(text);
                return false;
            })
        }else{
            $(ns.default.elInputValue.help).html('').hide();
        }

    };

    /************************************************************
     *  КАРТА С МЕТКАМИ
     *
     * Создает карту со всеми метками
     ************************************************************/

    this.createViewMap = function ($type) {
        ns.default.target_type = $type;
        ns.markersCurrent = {};

        ns.map = ns.createMap(ns.default.viewId);
        ns.markerCluster = new MarkerClusterer(ns.map, ns.markersCurrent,  {gridSize: 50, maxZoom: 15, imagePath: PATH_SKIN + '/images/marker/m'});


        google.maps.event.addListener(ns.map, 'idle', function () {
            ns.updateMarkers();
        });


        // Слушаем когда откроется метка
        window.addEventListener (
            "popstate",
            function (e) {

                /**
                 * Если увидели что есть параметр маркера, пробуем загрузить
                 */
                ns.validateHistoryState(e.state);
            },
            false
        );
        var activeId=ns.getActiveMarkerByUrl();

        if(activeId){
            ns.openMarkerInfo(activeId);
        }
    };


    this.validateHistoryState =function (state) {

    };



    this.updateMarkers=function () {
        ls.goolemap.getMarks(
            ns.map.getBounds().getSouthWest().lat(),
            ns.map.getBounds().getSouthWest().lng(),
            ns.map.getBounds().getNorthEast().lat(),
            ns.map.getBounds().getNorthEast().lng()
        );
    };
    /**
     *
     * @param x1
     * @param y1
     * @param x2
     * @param y2
     */
    this.getMarks=function(x1, y1, x2, y2) {
        filter = ns.getFilter();

        $.extend(filter, {
            sw_lat: x1,
            sw_lng: y1,
            ne_lat: x2,
            ne_lng: y2,
            target_type: ns.default.target_type
        });

        ls.ajax.load(aRouter.ajax + "getmarkers", filter, function (result) {
            ns.attachMarkers(result.aMaps);
        });


       /* $.ajax({
            url: ,
            method: 'post',
            data:
        }).success(function (result) {
            if (result.data) {
                ls.goolemap.setMarks(result.data);
            }
        });*/
    };

    /**
     * Обрабатывает метки и добавляет их на карту
     * @param list
     */
    this.attachMarkers=function(list, noRemove){
        // для начала удаляем те метки которых нет в текущей области

        if(!noRemove){
            var deletedMarkers = [];

            $.grep(Object.keys(ns.markersCurrent), function (k) {
                if (!list[k]) deletedMarkers.push(k);
            });

            $.each(deletedMarkers, function (k, el) {

                ns.markerCluster.removeMarker(ns.markersCurrent[el]);
                ns.markersCurrent[el].setMap(null);
                delete ns.markersCurrent[el];
            });
        }




        var icons = {
            default: {
                icon: ns.default.icons_url + 'marker.png'
            }
        };

        $.each(list, function (key, el) {
            if(!ns.markersCurrent[key]){

                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(el.lat, el.lng),
                    title: el.title,
                    icon: icons.default.icon,
                    map: ns.map
                });

                marker.addListener('click', function() {


                    ns.openMarkerInfo(el.id);
                });

                ns.markersCurrent[el.id]=marker;
                ns.markerCluster.addMarker(marker);
            }



        });


    };


    this.openMarkerInfo=function (id) {


        var url = ns.generateParam('marker', id);


        var state = {
            'marker': true,
            id: id
        };



        if(state.id){
            // если передан ид, запускаем ajax запрос на получение данных
            ls.ajax.load(aRouter['ajax']+'get-location-info', {id: state.id}, function (result) {
                if (result.bStateError) {
                    ls.msg.error(null, result.sMsg);
                } else {


                    var marker = ns.markersCurrent[state.id];

                    //ns.map.setZoom(ns.default.zoom.home);
                    if(marker){
                     //   ns.map.setCenter(marker.getPosition());

                    }
                    ns.attachMarkers(result.aMaps, true);

                    var $modal = $('.js-modalEmpty');

                    $modal.find('.modal-title').text(result.name);
                    $modal.find('.modal-body').html(result.sText);
                    $modal.modal('show');

                }
            })
        }else{
            console.error('id метки не найден');
        }

    };



    this.getFilter=function () {
       return {
           search: $(ns.default.filter.input_search).val() ? $(ns.default.filter.input_search).val() : null,
           category_id: $(ns.default.elInputValue.default_category).val(),
           city_id: $(ns.default.elInputValue.default_city).val()
       }
    };
    /**
     * Получаем список меток по городу
     * @param id
     */
    this.changeCity = function (id) {
        $(ns.default.elInputValue.default_city).val(id);
        ns.openAjaxCity(id)
    };

    this.changeCategory = function (id) {
        $(ns.default.elInputValue.default_category).val(id);
        ns.changeCurrentUrl('category_id', id)
    };



    this.returnList = function () {
        ns.openAjaxCity($(ns.default.elInputValue.default_city).val())
    };


    /**
     *
     */
    this.deleteLocation = function (id, callback) {
        ls.ajax.load(aRouter['ajax']+'remove-map-location', {id: id}, function (result) {
            if (result.bStateError) {
                ls.msg.error(null, result.sMsg);
            } else {
                ls.msg.notice(null, result.sMsg);
                callback();
            }
        })
    };

    this.search = function ($form) {
        var q = $form.find('input').first().val();
        ns.changeCurrentUrl('search', q);
        return false;
    };

    /****************************************************************************************
     **** HELPER
     ****************************************************************************************/


    this.changeCurrentUrl = function (param, value) {

        var url = ns.generateParam(param, value, (value === '' ? true : false), 'marker');

        var state = history.state ? history.state : {};
        state.category_id = value;

        window.history.replaceState(
            state,
            ('Метки в этом городе / '+$(document).find("title").text()),
            url
        );

        ns.updateMarkers();
    };

    this.getActiveMarkerByUrl = function () {
        var variable = 'marker';
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split("=");
            if (pair[0] == variable) {
                return pair[1];
            }
        }
        return (false);
    };


    this.generateParam = function (prmName, val, skip, aRemove, href) {
        var res = '';
        $href = href ? href : location.href;
        var d = $href.split("#")[0].split("?");
        var base = d[0];
        var query = d[1];
        if(query) {
            var params = query.split("&");
            for(var i = 0; i < params.length; i++) {
                var keyval = params[i].split("=");

                if(keyval[0] == aRemove ){

                    continue;
                }
                if(keyval[0] != prmName) {
                    if(i > 0){
                        res += '&';
                    }
                    res += params[i] ;

                }
            }
        }
        if(!skip) {
            res += '&'+ prmName + '=' + val;
        }

        return (base + '?' + res);

    };


    $(function () {

        $(document).on('click', '.js-switchCategory', function (e) {


            if(!$(this).hasClass('active')){
                $('.js-switchCategory').removeClass('active');
                ns.changeCategory($(this).data('category'));
                $(this).addClass('active');
            }else{
                $(this).removeClass('active');
                $('.js-switchCategory[data-category="all"]').addClass('active');

                ns.changeCategory('all');

            }
            return false;
        });

        $(document).on('click', '[data-toggle="marker"]', function (e) {
            ns.openMarkerInfo($(this).data('id'));
        });




    });



    return this;
}).call(ls.goolemap || {},jQuery);