/* eslint no-console: ["error", { allow: ["error"] }], max-nested-callbacks: ["error", 7] */
/**
 * @package    block_covid_notifications
 * @copyright  2021 onwards Celox Group
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Celox Group <info@celoxgroup.com.au>
 */

/**
 * @module block_advnotifications/notif
 */
 define(["jquery"], function ($) {
    // JQuery is available via $.
  
    return {
      initialise: function () {
        // Module initialised.
        $(document).ready(function () {
          // USER DISMISSING/CLICKING ON A NOTIFICATION.
          $(".block_covid_notifications").on(
            "click",
            ".dismissible",
            function () {
              var dismiss = $(this).attr("data-dismiss");
  
              $(this).slideUp("150", function () {
                $(this).remove();
              });
            }
          );
        });
      },
    };
  });
  