/**
 * Handles the Google login workflow.
 */
jQuery(function($) {
    var plusOnScriptEl = document.createElement('script'),
        referenceScriptEl = document.getElementsByTagName('script')[0],

        // Called when the auth attempt has completed
        s3GoogleOauthHandler = function(authResult) {
            // If authenticated...
            if (authResult.status.signed_in) {
                var expiresInMs = parseInt(authResult.expires_in) * 1000;

                $(document).trigger("tokenReceived.s3Demo");

                setUserName(authResult.access_token);

                // Get S3 credentials
                s3DemoGlobals.assumeRoleWithWebIdentity({
                    roleArn: "arn:aws:iam::164437588128:role/gaming_soon_developer_upload",
                    idToken: authResult.id_token
                });

                // Ensure the user is asked to re-auth before the token expires
                setTimeout(function() {
                    alert("Token expired. You must sign in again.");
                    $(document).trigger("tokenExpired.s3Demo");
                }, expiresInMs - 10000)
            }
            else {
                $(document).trigger("tokenExpired.s3Demo");
            }
        },

        showButton = function() {
            $("#google-signin").show();
        },

        // Grabs the authenticated user's name (for file storage)
        setUserName = function(accessToken) {
            var xhr = new XMLHttpRequest();

            xhr.onload = function() {
                if (xhr.status === 200) {
                    var responseText = JSON.parse(xhr.responseText)

                    s3DemoGlobals.userName = responseText.displayName;
                    s3DemoGlobals.userId = responseText.id;
                }
            };

            xhr.open("GET", "https://www.googleapis.com/plus/v1/people/me?access_token=" + accessToken);
            xhr.send();
        };

    window.s3GoogleOauthHandler = s3GoogleOauthHandler;

    // Setup the script tag used to load the SDK
    plusOnScriptEl.type = "text/javascript";
    plusOnScriptEl.async = true;
    plusOnScriptEl.src = "https://plus.google.com/js/client:plusone.js";
    referenceScriptEl.parentNode.insertBefore(plusOnScriptEl, referenceScriptEl);

    $(document).on("tokenExpired.s3Demo", showButton);
    $(document).on("tokenReceived.s3Demo", function() {
        $("#google-signin").hide();
    });

});
