var Confirm = {
    modalContainerId: "#modal-container",
    modalBackgroundId: "#modal-background",
    modalMainId: "#confirm-modal",
    customButton: {
        Okay: {
            primary: !0,
            callback: function() {
                Confirm.hide();
            }
        }
    },
    customEvent: null,
    init: function(o) {
        $(this.modalMainId).remove(), $("body").append('<div id="confirm-modal" class="modal fade role="dialog" tabindex="-1"><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-header"><button id="modal-upper-close" class="close modal-close" aria-label="Close" type="button" title="Close"><span aria-hidden="true">×</span></button><h4 id="modal-title" class="modal-title">Modal Title</h4></div><div id="modal-body" class="modal-body"> Modal Message </div><div id="modal-footer" class="modal-footer"></div></div></div></div><div id="modal-background" class=""></div>');
    },
    addCustomButtons: function() {
        var d = this;
        $(".modal-custom-button").remove(), closeButton = "", void 0 !== d.customButton ? 1 == Object.keys(d.customButton).length && (closeButton = '<button id="modal-close" type="button" class="btn btn-default btn-xs modal-custom-button">Close</button>') : d.customButton = {
            Okay: {
                primary: !0,
                callback: function() {
                    Confirm.hide();
                }
            }
        }, $.each(d.customButton, function(o, t) {
            buttonName = o.replace(/ /g, "");
            var a, o = "btn-default";
            t.primary && (o = "btn-primary"), "okay" != buttonName.toLowerCase() && "ok" != buttonName.toLowerCase() || (closeButton = ""), 
            "delete" != buttonName.toLowerCase() && "remove" != buttonName.toLowerCase() || (o = "btn-danger"), 
            a = closeButton + '<button id="button-' + buttonName.toLowerCase() + '" type="button" class="btn btn-xs modal-custom-button ' + o + '">' + buttonName + "</button>", 
            $("#modal-footer").append(a), $("#modal-close") && (closeButton = ""), d.addCustomButtonEvents(buttonName.toLowerCase(), t.callback);
        }), $("#modal-upper-close").unbind(), $("#modal-upper-close").bind("click", function(o) {
            o.preventDefault(), d.hide();
        }), $("#modal-close").unbind(), $("#modal-close").bind("click", function(o) {
            o.preventDefault(), d.hide();
        });
    },
    addCustomButtonEvents: function(o, t) {
        $("#button-" + o).unbind(), $("#button-" + o).bind("click", function(o) {
            o.preventDefault(), t();
        });
    },
    show: function(o, t, a) {
        var d = this;
        o && $("#modal-title").html(o), t && $("#modal-body").html(t), d.customButton = a, 
        $(d.modalMainId).addClass("in"), $(d.modalBackgroundId).addClass("modal-backdrop fade in"), 
        $(d.modalMainId).css({
            display: "block",
            "padding-right": "17px"
        }), d.addCustomButtons();
    },
    hide: function() {
        var o = this;
        $(o.modalMainId).removeClass("in"), $(o.modalBackgroundId).removeClass("modal-backdrop fade in"), 
        $(o.modalMainId).css("display", "none");
    }
};

$(document).ready(function() {
    Confirm.init();
});