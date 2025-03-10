import "./bootstrap";
import * as bootstrap from "bootstrap";
import "../css/app.css";
import "@fortawesome/fontawesome-free/css/all.css";
import jQuery from "jquery";
import "jquery-validation";
import "datatables.net-bs5";
import "datatables.net-bs5/css/dataTables.bootstrap5.min.css";

import moment from "moment";

window.$ = jQuery;
window.jQuery = jQuery;
window.moment = moment;
window.bootstrap = bootstrap;

$(document).ready(function () {
    $.validator.setDefaults({
        errorElement: "span",
        errorClass: "invalid-feedback",
        errorPlacement: function (error, element) {
            error.insertAfter(element);
        },
        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        },
    });
});
