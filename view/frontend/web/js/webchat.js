define(['jquery', 'domReady!'], function ($) {
    'use strict';

    /**
     * Webchat JS code
     * @param {String} apiSpaceId
     * @param {String} currentMagentoUserId
     */
    return function (config) {
        // Load the widget
        window.comapiConfig = {
            apiSpace: config.apiSpaceId,
            launchTimeout: 2000
        };
        (function (d, s, id) {
            var js, cjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) { return; }
            js = d.createElement(s); js.id = id;
            js.src = '//cdn.dnky.co/widget/bootstrap.js';
            cjs.parentNode.insertBefore(js, cjs);
        }(document, 'script', 'comapi-widget'));

        window.addEventListener("message", function (event) {
            if (event.data.type === "WidgetInitialised") {
                console.log("Webchat WidgetInitialised", event.data.session);
                const MAGENTO_DETAILS_KEY = "ddg_cpaas.currentMagentoDetails";

                // Sync the Magento user id
                window.COMAPI_WIDGET_API.profile.getProfile()
                    .then(function (profile) {
                        if ((config.currentMagentoUserId != "" && config.currentMagentoUserId != sessionStorage.getItem(MAGENTO_DETAILS_KEY)) || config.currentProfileId == "") {
                            $.ajax({
                                url: "/connector/Cpaas/UpdateUserDetails",
                                type: "POST",
                                data: "comapiProfileId=" + profile.id,
                                success: function (result) {
                                    // Call worked
                                    console.log("Magento user details sent to chat");
                                    sessionStorage.setItem(MAGENTO_DETAILS_KEY, config.currentMagentoUserId);
                                },
                                error: function (xhr, status, error) {
                                    // Call failed
                                    console.log("Call failed whilst trying to update Magento user details for chat: " + error);
                                    sessionStorage.setItem(MAGENTO_DETAILS_KEY, "");
                                }
                            });
                        }
                    })
                    .catch(function (error) {
                        console.error("getProfile() failed", error);
                    });
            }
        });

    }
});