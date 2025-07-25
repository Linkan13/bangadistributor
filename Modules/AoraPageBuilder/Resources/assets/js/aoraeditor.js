$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

!function (t, n) {
    if ("object" == typeof exports && "object" == typeof module) module.exports = n(require("jQuery")); else if ("function" == typeof define && define.amd) define(["jQuery"], n); else {
        var e = "object" == typeof exports ? n(require("jQuery")) : n(t.jQuery);
        for (var o in e) ("object" == typeof exports ? exports : t)[o] = e[o]
    }
}("undefined" != typeof self ? self : this, (function (t) {
    return function (t) {
        var n = {};

        function e(o) {
            if (n[o]) return n[o].exports;
            var a = n[o] = {i: o, l: !1, exports: {}};
            return t[o].call(a.exports, a, a.exports, e), a.l = !0, a.exports
        }

        return e.m = t, e.c = n, e.d = function (t, n, o) {
            e.o(t, n) || Object.defineProperty(t, n, {enumerable: !0, get: o})
        }, e.r = function (t) {
            "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(t, Symbol.toStringTag, {value: "Module"}), Object.defineProperty(t, "__esModule", {value: !0})
        }, e.t = function (t, n) {
            if (1 & n && (t = e(t)), 8 & n) return t;
            if (4 & n && "object" == typeof t && t && t.__esModule) return t;
            var o = Object.create(null);
            if (e.r(o), Object.defineProperty(o, "default", {
                enumerable: !0, value: t
            }), 2 & n && "string" != typeof t) for (var a in t) e.d(o, a, function (n) {
                return t[n]
            }.bind(null, a));
            return o
        }, e.n = function (t) {
            var n = t && t.__esModule ? function () {
                return t.default
            } : function () {
                return t
            };
            return e.d(n, "a", n), n
        }, e.o = function (t, n) {
            return Object.prototype.hasOwnProperty.call(t, n)
        }, e.p = "", e(e.s = 6)
    }([, function (n, e) {
        n.exports = t
    }, , function (t, n, e) {
    }, , , function (t, n, e) {
        "use strict";
        e.r(n);
        e(3);
        var o = e(1), a = e.n(o), c = {
            UI: "aoraeditor-ui",
            UI_DRAGGING: "aoraeditor-ui-dragging",
            UI_HIDDEN: "aoraeditor-ui-hidden",
            UI_CUTTING: "aoraeditor-ui-cutting",
            UI_RESIZER: "ui-resizable-resizer",
            SORTABLE: "ui-sortable",
            RESIZABLE: "ui-sortable",
            WRAPPER: "aoraeditor-wrapper",
            BTN: "aoraeditor-btn",
            BTN_GROUP: "aoraeditor-btn-group",
            BTN_PRIMARY: "aoraeditor-btn-primary",
            BTN_DEFAULT: "aoraeditor-btn-default",
            STATE_ACTIVE: "active",
            STATE_OPENED: "opened",
            STATE_SHOWED: "showed",
            STATE_SELECTED: "selected",
            STATE_DUPLICATED: "duplicated",
            STATE_INITIALIZED: "initialized",
            STATE_INITIALIZING: "initializing",
            STATE_PREVIEWING: "previewing",
            STATE_TOOLBAR_SHOWED: "showed-aoraeditor-toolbar",
            STATE_SIDEBAR_SHOWED: "showed-aoraeditor-sidebar",
            STATE_MODAL_OPENED: "opened-modal",
            STATE_NOT_MATCHED: "not-matched",
            STATE_HAS_FOOTER: "has-footer",
            STATE_COPYING: "copying",
            STATE_COPYING_COMPONENT: "copying-component",
            STATE_COPYING_CONTAINER: "copying-container",
            STATE_COPYING_SUB_CONTAINER: "copying-sub-container",
            ADD_CONTENT: "btn-add-content",
            PASTE_CONTENT: "btn-paste-content",
            SIDEBAR: "aoraeditor-sidebar",
            SIDEBAR_HEADER: "aoraeditor-sidebar-header",
            SIDEBAR_BODY: "aoraeditor-sidebar-body",
            SIDEBAR_TITLE: "aoraeditor-sidebar-title",
            SIDEBAR_CLOSER: "aoraeditor-sidebar-closer",
            TOPBAR: "aoraeditor-topbar hide-desktop",
            TOPBAR_LEFT: "aoraeditor-topbar-left",
            TOPBAR_CENTER: "aoraeditor-topbar-center",
            TOPBAR_RIGHT: "aoraeditor-topbar-right",
            TOPBAR_BUTTON: "aoraeditor-topbar-btn",
            TOPBAR_TITLE: "aoraeditor-topbar-title",
            MODAL: "aoraeditor-modal",
            MODAL_COMPONENT: "aoraeditor-modal-component",
            MODAL_CONTAINER: "aoraeditor-modal-container",
            MODAL_HEADER: "aoraeditor-modal-header",
            MODAL_TITLE: "aoraeditor-modal-title",
            MODAL_BODY: "aoraeditor-modal-body",
            MODAL_FOOTER: "aoraeditor-modal-footer",
            MODAL_CLOSE: "aoraeditor-modal-close",
            MODAL_ADD: "aoraeditor-modal-add",
            SNIPPET: "aoraeditor-snippet",
            SNIPPET_INNER: "aoraeditor-snippet-inner",
            SNIPPET_TITLE: "aoraeditor-snippet-title",
            SNIPPET_PREVIEW: "aoraeditor-snippet-preview",
            SNIPPET_CONTAINER: "aoraeditor-snippet-container",
            SNIPPET_COMPONENT: "aoraeditor-snippet-component",
            SNIPPETS: "aoraeditor-snippets",
            SNIPPETS_FILTER: "aoraeditor-snippets-filter",
            SNIPPETS_FILTER_COMPONENT: "aoraeditor-snippets-filter-component",
            SNIPPETS_FILTER_CONTAINER: "aoraeditor-snippets-filter-container",
            SNIPPETS_FILTER_LABEL: "aoraeditor-snippets-filter-label",
            SNIPPETS_SEARCH: "aoraeditor-snippets-search",
            SNIPPETS_FILTER_WRAPPER: "aoraeditor-snippets-filter-wrapper",
            SNIPPETS_COMPONENT: "aoraeditor-snippets-component",
            SNIPPETS_WRAPPER: "aoraeditor-snippets-wrapper",
            TOOLBAR: "aoraeditor-toolbar",
            TOOLBAR_BOTTOM: "aoraeditor-toolbar-bottom",
            TOOLBAR_CONTENT_AREA: "aoraeditor-toolbar-content-area",
            TOOLBAR_CONTAINER: "aoraeditor-toolbar-container",
            TOOLBAR_CONTAINER_BOTTOM: "aoraeditor-toolbar-bottom-container",
            TOOLBAR_CONTAINER_CONTENT: "aoraeditor-toolbar-container-content",
            TOOLBAR_SUB_CONTAINER: "aoraeditor-toolbar-sub-container",
            TOOLBAR_SUB_CONTAINER_BOTTOM: "aoraeditor-toolbar-bottom-sub-container",
            TOOLBAR_SUB_CONTAINER_CONTENT: "aoraeditor-toolbar-sub-container-content",
            TOOLBAR_COMPONENT: "aoraeditor-toolbar-component",
            TOOLBAR_COMPONENT_BOTTOM: "aoraeditor-toolbar-bottom-component",
            SETTING: "aoraeditor-setting",
            SETTING_FORM: "aoraeditor-setting-form",
            SETTING_FORM_LOADING: "aoraeditor-setting-form-loading",
            CONTENT_AREA: "aoraeditor-content-area",
            CONTENT_AREA_INNER: "aoraeditor-content-area-inner",
            CONTENT_AREAS: "aoraeditor-content-areas",
            CONTENT_AREAS_WRAPPER: "aoraeditor-content-areas-wrapper",
            IFRAME: "aoraeditor-iframe",
            IFRAME_BODY: "aoraeditor-iframe-body",
            IFRAME_WRAPPER: "aoraeditor-iframe-wrapper",
            IFRAME_WIDTH_SWITCHER: "aoraeditor-iframe-width-switcher",
            IFRAME_COVER_WRAPPER: "aoraeditor-iframe-cover-wrapper",
            IFRAME_COVER_WRAPPER_FAKE: "aoraeditor-iframe-cover-wrapper-fake",
            IFRAME_COVER: "aoraeditor-iframe-cover",
            IFRAME_COVER_HIDDEN: "hidden-cover",
            COMPONENT: "aoraeditor-component",
            COMPONENT_MOVE: "btn-component-move",
            COMPONENT_MOVE_UP: "btn-component-move-up",
            COMPONENT_MOVE_DOWN: "btn-component-move-down",
            COMPONENT_SETTING: "btn-component-setting",
            COMPONENT_DUPLICATE: "btn-component-duplicate",
            COMPONENT_COPY: "btn-component-copy",
            COMPONENT_CUT: "btn-component-cut",
            COMPONENT_DELETE: "btn-component-delete",
            COMPONENT_CONTENT: "aoraeditor-component-content",
            COMPONENT_EXISTING: "existing-component",
            CONTAINER: "aoraeditor-container",
            CONTAINER_MOVE: "btn-container-move",
            CONTAINER_MOVE_UP: "btn-container-move-up",
            CONTAINER_MOVE_DOWN: "btn-container-move-down",
            CONTAINER_DUPLICATE: "btn-container-duplicate",
            CONTAINER_COPY: "btn-container-copy",
            CONTAINER_CUT: "btn-container-cut",
            CONTAINER_DELETE: "btn-container-delete",
            CONTAINER_SETTING: "btn-container-setting",
            CONTAINER_INNER: "aoraeditor-container-inner",
            CONTAINER_CONTENT: "aoraeditor-container-content",
            CONTAINER_CONTENT_INNER: "aoraeditor-container-content-inner",
            SUB_CONTAINER: "aoraeditor-sub-container",
            SUB_CONTAINER_CONTENT: "aoraeditor-sub-container-content",
            PREVIEW_AREA: "aoraeditor-preview-area"
        }, i = {
            title: window.jsLang('data_aora_editor'),
            containerForQuickAddComponent: '\n        <div class="row">\n            <div class="col-sm-12" data-type="container-content">\n            </div>\n        </div>\n    ',
            extraSettings: null,
            extraTopbarItems: null,
            locale: {
                viewOnMobile: window.jsLang('data_view_mobile'),
                viewOnTablet: window.jsLang('data_view_tablet'),
                viewOnLaptop: window.jsLang('data_view_laptop'),
                viewOnDesktop: window.jsLang('data_view_desktop'),
                previewOn: window.jsLang('data_preview_on'),
                previewOff: window.jsLang('data_preview_off'),
                fullscreenOn: window.jsLang('data_fullscreen_on'),
                fullscreenOff: window.jsLang('data_fullscreen_off'),
                save: window.jsLang('data_save'),
                addContent: window.jsLang('data_add_content'),
                addContentBelow: window.jsLang('data_add_content_below'),
                pasteContent: window.jsLang('data_paste_content'),
                pasteContentBelow: window.jsLang('data_paste_content_below'),
                move: window.jsLang('data_drag'),
                moveUp: window.jsLang('data_move_up'),
                moveDown: window.jsLang('data_move_down'),
                setting: window.jsLang('data_setting'),
                copy: window.jsLang('data_copy'),
                cut: window.jsLang('data_cut'),
                delete: window.jsLang('data_delete'),
                snippetCategoryLabel: window.jsLang('data_category'),
                snippetCategoryAll: window.jsLang('data_all'),
                snippetCategorySearch: window.jsLang('data_type_to_search'),
                columnResizeTitle: window.jsLang('data_drag_to_resize'),
                containerSetting: window.jsLang('data_container_settings'),
                confirmDeleteContainerText: window.jsLang('data_confirm_delete_container'),
                confirmDeleteComponentText: window.jsLang('data_confirm_delete_component')
            },
            widthMobile: 420,
            widthTablet: 820,
            widthLaptop: 1050,
            minWidthDesktop: 1250,
            defaultComponentType: "blank",
            snippetsUrl: "snippets/snippets.html",
            snippetsCategoriesSeparator: ";",
            contentStyles: [],
            contentAreasSelector: null,
            contentAreasWrapper: '<div class="'.concat(c.UI, " ").concat(c.CONTENT_AREAS_WRAPPER, '"></div>'),
            containerSettingEnabled: !1,
            containerSettingInitFunction: null,
            containerSettingShowFunction: null,
            containerSettingHideFunction: null,
            bootstrap: {
                columnResizeEnabled: !0,
                deviceClass: {MOBILE: "xs", TABLET: "sm", LAPTOP: "md", DESKTOP: "lg"},
                gridSystem: [{width: 8.33333333, col: 1}, {width: 16.66666667, col: 2}, {
                    width: 25, col: 3
                }, {width: 33.33333333, col: 4}, {width: 41.66666667, col: 5}, {width: 50, col: 6}, {
                    width: 58.33333333, col: 7
                }, {width: 66.66666667, col: 8}, {width: 75, col: 9}, {
                    width: 83.33333333, col: 10
                }, {width: 91.66666667, col: 11}, {width: 100, col: 12}, {width: 1e4, col: 1e4}]
            },
            clickComponentToShowSetting: !1,
            onReady: function () {
            },
            onSave: function (t) {
            },
            onSnippetsLoaded: function (t) {
            },
            onSnippetsError: function (t) {
            },
            onInitIframe: function (t, n, e) {
            },
            onContentChanged: function (t, n) {
            },
            onBeforeInitContentArea: function (t) {
            },
            onInitContentArea: function (t) {
                return t.children(".".concat(c.CONTENT_AREA_INNER)).children()
            },
            onBeforeInitContainer: function (t, n) {
            },
            onInitContainer: function (t, n) {
            },
            onBeforeContainerDeleted: function (t, n, e) {
            },
            onContainerDeleted: function (t, n, e) {
            },
            onContainerChanged: function (t, n, e) {
            },
            onContainerDuplicated: function (t, n, e, o) {
            },
            onContainerSelected: function (t, n, e) {
            },
            onContainerSnippetAdded: function (t, n, e, o) {
            },
            onComponentReady: function (t) {
            },
            onBeforeInitComponent: function (t, n) {
            },
            onInitComponent: function (t, n) {
            },
            onBeforeComponentDeleted: function (t, n, e) {
            },
            onComponentDeleted: function (t, n, e) {
            },
            onComponentChanged: function (t, n, e) {
            },
            onComponentDuplicated: function (t, n, e, o) {
            },
            onComponentSelected: function (t, n, e) {
            },
            onComponentSnippetAdded: function (t, n, e, o) {
            },
            onBeforeDynamicContentLoad: function (t, n, e) {
            },
            onDynamicContentLoaded: function (t, n, e) {
            },
            onDynamicContentError: function (t, n, e) {
            }
        }, r = function () {
            if (console && "function" == typeof console.log && window.AORADEDITOR_DEBUG) {
                for (var t = arguments.length, n = new Array(t), e = 0; e < t; e++) n[e] = arguments[e];
                console.log.apply(console, ["[ aoraEditor ] "].concat(n))
            }
        }, l = function (t) {
            throw new Error("[ aoraEditor ] ".concat(t))
        }, s = function () {
            var t = (new Date).getTime(), n = Math.round(9876543210 * Math.random());
            return "aoraeditor-ui-".concat(t).concat(n)
        }, T = function (t, n, e) {
            var o = e ? [] : {};
            return n || (n = []), $.each(t.get(0).attributes, (function (t, a) {
                0 === a.name.indexOf("data-") && -1 === $.inArray(a.name, n) && (e ? o.push("".concat(a.name, '="').concat(a.value, '"')) : o[a.name] = a.value)
            })), o
        }, E = function (t, n) {
            n || (t.wrap('<div class="'.concat(c.IFRAME_COVER_WRAPPER_FAKE, '"></div>')), n = t.parent()), n.addClass("".concat(c.IFRAME_COVER_WRAPPER));
            var e = $('<div class="'.concat(c.IFRAME_COVER, '"></div>'));
            n.prepend(e), n.on("mouseleave", (function () {
                n.removeClass("".concat(c.IFRAME_COVER_HIDDEN))
            })), e.on("dblclick", (function (t) {
                t.preventDefault(), n.addClass("".concat(c.IFRAME_COVER_HIDDEN))
            }))
        }, d = function (t, n, e, o, a, i) {
            var r = this.options, l = s(),
                T = '\n        <section\n            class="'.concat(c.UI, " ").concat(c.SNIPPET, " ").concat("container" === t ? c.SNIPPET_CONTAINER : c.SNIPPET_COMPONENT, '"\n            data-snippet="#').concat(l, '"\n            data-type="').concat(t, '"\n            data-aoraeditor-title="').concat(n, '"\n            data-aoraeditor-categories="').concat(o, '"\n        >\n            <span class="').concat(c.SNIPPET_INNER, '">\n                <span class="').concat(c.SNIPPET_PREVIEW, '" style="background-image: url(\'').concat(e, '\')"></span>\n                <span class="').concat(c.SNIPPET_TITLE, '" title="').concat(n, '">').concat(n, "</span>\n            </span>\n        </section>\n    "),
                E = '<script id="'.concat(l, '" type="text/html" ').concat(i.join(" "), ">").concat(a, "<\/script>");
            return o = o.split(r.snippetsCategoriesSeparator), "container" === t ? this.categoryContainer = this.categoryContainer.concat(o) : -1 !== t.indexOf("component") && (this.categoryComponent = this.categoryComponent.concat(o)), [T, E]
        }, O = function () {
            var t = this, n = t.options, e = t.modal,
                o = '<option value="" selected="selected">'.concat(n.locale.snippetCategoryAll, "</option>");
            $.each(t.categoryComponent, (function (t, n) {
                o += '<option value="'.concat(n, '" class="').concat(c.SNIPPETS_FILTER_COMPONENT, '">').concat(n, "</option>")
            })), $.each(t.categoryContainer, (function (n, e) {
                var a = -1 !== $.inArray(e, t.categoryComponent);
                o += '<option value="'.concat(e, '" class="').concat(c.SNIPPETS_FILTER_CONTAINER, " ").concat(a ? c.STATE_DUPLICATED : "", '">').concat(e, "</option>")
            }));
            var a = e.find(".".concat(c.SNIPPETS_FILTER_WRAPPER));
            return 0 === a.length && (a = $('<div class="'.concat(c.UI, " ").concat(c.SNIPPETS_FILTER_WRAPPER, '"></div>')), e.find(".".concat(c.MODAL_TITLE)).replaceWith(a)), [o, a]
        }, N = function (t) {
            for (var n = [], e = 0; e < t.length; e++) {
                var o = t[e] || "";
                "" !== o && -1 === $.inArray(o, n) && n.push(o)
            }
            return n.sort()
        };

        function p(t, n) {
            return function (t) {
                if (Array.isArray(t)) return t
            }(t) || function (t, n) {
                if ("undefined" == typeof Symbol || !(Symbol.iterator in Object(t))) return;
                var e = [], o = !0, a = !1, c = void 0;
                try {
                    for (var i, r = t[Symbol.iterator](); !(o = (i = r.next()).done) && (e.push(i.value), !n || e.length !== n); o = !0) ;
                } catch (t) {
                    a = !0, c = t
                } finally {
                    try {
                        o || null == r.return || r.return()
                    } finally {
                        if (a) throw c
                    }
                }
                return e
            }(t, n) || function (t, n) {
                if (!t) return;
                if ("string" == typeof t) return f(t, n);
                var e = Object.prototype.toString.call(t).slice(8, -1);
                "Object" === e && t.constructor && (e = t.constructor.name);
                if ("Map" === e || "Set" === e) return Array.from(t);
                if ("Arguments" === e || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(e)) return f(t, n)
            }(t, n) || function () {
                throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")
            }()
        }

        function f(t, n) {
            (null == n || n > t.length) && (n = t.length);
            for (var e = 0, o = new Array(n); e < n; e++) o[e] = t[e];
            return o
        }

        var C = function (t, n, e, o, a, i) {
            var r = p(d.call(this, t, n, e, o, a, i), 2), l = r[0], s = r[1];
            this.categoryContainer = N(this.categoryContainer), this.categoryComponent = N(this.categoryComponent), this.modal.find(".".concat(c.SNIPPETS)).append(l), this.modal.find(".".concat(c.MODAL_BODY)).append(s);
            var T = p(O.call(this), 2), E = T[0];
            T[1].find(".".concat(c.SNIPPETS_FILTER)).html(E).trigger("change")
        };

        function u(t, n) {
            return function (t) {
                if (Array.isArray(t)) return t
            }(t) || function (t, n) {
                if ("undefined" == typeof Symbol || !(Symbol.iterator in Object(t))) return;
                var e = [], o = !0, a = !1, c = void 0;
                try {
                    for (var i, r = t[Symbol.iterator](); !(o = (i = r.next()).done) && (e.push(i.value), !n || e.length !== n); o = !0) ;
                } catch (t) {
                    a = !0, c = t
                } finally {
                    try {
                        o || null == r.return || r.return()
                    } finally {
                        if (a) throw c
                    }
                }
                return e
            }(t, n) || function (t, n) {
                if (!t) return;
                if ("string" == typeof t) return A(t, n);
                var e = Object.prototype.toString.call(t).slice(8, -1);
                "Object" === e && t.constructor && (e = t.constructor.name);
                if ("Map" === e || "Set" === e) return Array.from(t);
                if ("Arguments" === e || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(e)) return A(t, n)
            }(t, n) || function () {
                throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")
            }()
        }

        function A(t, n) {
            (null == n || n > t.length) && (n = t.length);
            for (var e = 0, o = new Array(n); e < n; e++) o[e] = t[e];
            return o
        }

        var h = function (t) {
            var n = this, e = "", o = "";
            $(t).filter("div").each((function () {
                var t = $(this), a = t.html().trim(), c = t.attr("data-preview"), i = t.attr("data-type"),
                    r = t.attr("data-aoraeditor-title"), l = t.attr("data-aoraeditor-categories") || "",
                    s = n.getDataAttributes(t, ["data-preview", "data-type", "data-aoraeditor-title", "data-aoraeditor-categories"], !0),
                    T = u(d.call(n, i, r, c, l, a, s), 2), E = T[0], O = T[1];
                o += E, e += O
            })), n.categoryContainer = N(n.categoryContainer), n.categoryComponent = N(n.categoryComponent), n.modal.find(".".concat(c.SNIPPETS)).html(o), n.modal.find(".".concat(c.MODAL_BODY)).append(e)
        };

        function _(t, n) {
            return function (t) {
                if (Array.isArray(t)) return t
            }(t) || function (t, n) {
                if ("undefined" == typeof Symbol || !(Symbol.iterator in Object(t))) return;
                var e = [], o = !0, a = !1, c = void 0;
                try {
                    for (var i, r = t[Symbol.iterator](); !(o = (i = r.next()).done) && (e.push(i.value), !n || e.length !== n); o = !0) ;
                } catch (t) {
                    a = !0, c = t
                } finally {
                    try {
                        o || null == r.return || r.return()
                    } finally {
                        if (a) throw c
                    }
                }
                return e
            }(t, n) || function (t, n) {
                if (!t) return;
                if ("string" == typeof t) return I(t, n);
                var e = Object.prototype.toString.call(t).slice(8, -1);
                "Object" === e && t.constructor && (e = t.constructor.name);
                if ("Map" === e || "Set" === e) return Array.from(t);
                if ("Arguments" === e || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(e)) return I(t, n)
            }(t, n) || function () {
                throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")
            }()
        }

        function I(t, n) {
            (null == n || n > t.length) && (n = t.length);
            for (var e = 0, o = new Array(n); e < n; e++) o[e] = t[e];
            return o
        }

        var v = function () {
            var t = this, n = t.options, e = _(O.call(t), 2), o = e[0], a = e[1];
            a.html('\n        <span class="'.concat(c.UI, " ").concat(c.SNIPPETS_FILTER_LABEL, '">').concat(n.locale.snippetCategoryLabel, ':</span>\n        <select class="').concat(c.UI, " ").concat(c.SNIPPETS_FILTER, '">\n            ').concat(o, '\n        </select>\n        <input type="text" class="').concat(c.UI, " ").concat(c.SNIPPETS_SEARCH, '" value="" placeholder="').concat(n.locale.snippetCategorySearch, '" />\n    '));
            var i, r = a.find(".".concat(c.SNIPPETS_SEARCH)), l = a.find(".".concat(c.SNIPPETS_FILTER)),
                s = function () {
                    var e = (l.val() || "").toLowerCase(), o = (r.val() || "").toLowerCase(),
                        a = t.modal.find(".".concat(c.SNIPPET));
                    a.filter(".".concat(c.STATE_SELECTED)).removeClass(c.STATE_SELECTED), e || o ? a.each((function () {
                        var t = $(this), a = t.attr("data-aoraeditor-categories").toLowerCase(),
                            i = a.split(n.snippetsCategoriesSeparator), r = 0;
                        (e && -1 === $.inArray(e, i) && r++, o) && (-1 === t.attr("data-aoraeditor-title").toLowerCase().indexOf(o) && -1 === a.indexOf(o) && r++);
                        t[0 === r ? "removeClass" : "addClass"](c.STATE_NOT_MATCHED)
                    })) : a.removeClass(c.STATE_NOT_MATCHED)
                };
            l.on("change", (function () {
                s()
            })), r.on("keydown", (function () {
                clearTimeout(i), i = setTimeout(s, 200)
            }))
        }, R = function (t) {
            var n = "webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend";
            t.off(n).on(n, (function () {
                t.hasClass(c.STATE_SHOWED) || (t.css("display", "none"), $(document.body).removeClass(c.STATE_MODAL_OPENED))
            })), t.removeClass(c.STATE_SHOWED)
        }, m = function () {
            var t = this.modal;
            this.modalTarget = null, this.modalTargetAction = null, t.find(".".concat(c.STATE_SELECTED)).removeClass(c.STATE_SELECTED), t.find(".".concat(c.STATE_NOT_MATCHED)).removeClass(c.STATE_NOT_MATCHED), t.find(".".concat(c.SNIPPETS_FILTER)).val(""), t.removeClass(c.MODAL_COMPONENT), t.removeClass(c.MODAL_CONTAINER), R.call(this, t)
        }, S = function () {
            var t = this.modal;
            t.on({
                click: function (n) {
                    n.preventDefault();
                    var e = $(this);
                    e.hasClass(c.STATE_SELECTED) ? e.removeClass(c.STATE_SELECTED) : (t.find(".".concat(c.STATE_SELECTED)).removeClass(c.STATE_SELECTED), e.addClass(c.STATE_SELECTED))
                }, mouseover: function () {
                    $(this).addClass(c.STATE_SELECTED)
                }, mouseout: function () {
                    $(this).removeClass(c.STATE_SELECTED)
                }
            }, ".".concat(c.SNIPPET))
        }, y = {APPEND: "append", AFTER: "after"}, P = {
            CONTENT_AREA: 1e3,
            CONTAINER: 2e3,
            CONTAINER_CONTENT: 2100,
            CONTAINER_BOTTOM: 2200,
            SUB_CONTAINER: 3e3,
            SUB_CONTAINER_BOTTOM: 3100,
            SUB_CONTAINER_CONTENT: 3200,
            COMPONENT: 4e3,
            COMPONENT_BOTTOM: 4100
        }, D = {
            // ADD_CONTENT: '<i class="fa fa-plus"></i>',
            // PASTE_CONTENT: '<i class="fa fa-paste"></i>',
            // MOVE_CONTAINER: '<i class="fa fa-arrows-v"></i>',
            // MOVE_COMPONENT: '<i class="fa fa-arrows"></i>',
            // MOVE_UP_CONTAINER: '<i class="fa fa-chevron-up"></i>',
            // MOVE_UP_COMPONENT: '<i class="fa fa-chevron-up"></i>',
            // MOVE_DOWN_CONTAINER: '<i class="fa fa-chevron-down"></i>',
            // MOVE_DOWN_COMPONENT: '<i class="fa fa-chevron-down"></i>',
            // SETTING_CONTAINER: '<i class="fa fa-cog"></i>',
            // SETTING_COMPONENT: '<i class="fa fa-cog"></i>',
            // DUPLICATE_CONTAINER: '<i class="fa fa-files-o"></i>',
            // DUPLICATE_COMPONENT: '<i class="fa fa-files-o"></i>',
            // COPY_CONTAINER: '<i class="fa fa-files-o"></i>',
            // COPY_COMPONENT: '<i class="fa fa-files-o"></i>',
            // CUT_CONTAINER: '<i class="fa fa-cut"></i>',
            // CUT_COMPONENT: '<i class="fa fa-cut"></i>',
            // DELETE_CONTAINER: '<i class="fa fa-trash-o"></i>',
            // DELETE_COMPONENT: '<i class="fa fa-trash-o"></i>',
            // DEVICE_MOBILE: '<i class="fa fa-fw fa-mobile"></i>',
            // DEVICE_TABLET: '<i class="fa fa-fw fa-tablet"></i>',
            // DEVICE_LAPTOP: '<i class="fa fa-fw fa-laptop"></i>',
            // DEVICE_DESKTOP: '<i class="fa fa-fw fa-desktop"></i>',
            // PREVIEW_ON: '<i class="fa fa-fw fa-eye"></i>',
            // PREVIEW_OFF: '<i class="fa fa-fw fa-eye-slash"></i>',
            // FULLSCREEN_ON: '<i class="fa fa-fw fa-compress"></i>',
            // FULLSCREEN_OFF: '<i class="fa fa-fw fa-expand"></i>',
            // SAVE: '<i class="fa fa-fw fa-save"></i>',
            // ADD: '<i class="fa fa-plus"></i>'
            ADD_CONTENT: '<i class="fas fa-plus"></i>',
            PASTE_CONTENT: '<i class="fas fa-paste"></i>',
            MOVE_CONTAINER: '<i class="fas fa-arrows-alt-v"></i>',
            MOVE_COMPONENT: '<i class="fas fa-arrows-alt-h"></i>',
            MOVE_UP_CONTAINER: '<i class="fas fa-chevron-up"></i>',
            MOVE_UP_COMPONENT: '<i class="fas fa-chevron-up"></i>',
            MOVE_DOWN_CONTAINER: '<i class="fas fa-chevron-down"></i>',
            MOVE_DOWN_COMPONENT: '<i class="fas fa-chevron-down"></i>',
            SETTING_CONTAINER: '<i class="fas fa-cog"></i>',
            SETTING_COMPONENT: '<i class="fas fa-cog"></i>',
            DUPLICATE_CONTAINER: '<i class="far fa-copy"></i>',
            DUPLICATE_COMPONENT: '<i class="far fa-copy"></i>',
            COPY_CONTAINER: '<i class="far fa-copy"></i>',
            COPY_COMPONENT: '<i class="far fa-copy"></i>',
            CUT_CONTAINER: '<i class="fas fa-cut"></i>',
            CUT_COMPONENT: '<i class="fas fa-cut"></i>',
            DELETE_CONTAINER: '<i class="far fa-trash-alt"></i>',
            DELETE_COMPONENT: '<i class="far fa-trash-alt"></i>',
            DEVICE_MOBILE: '<i class="fas fa-mobile-alt"></i>',
            DEVICE_TABLET: '<i class="fas fa-tablet-alt"></i>',
            DEVICE_LAPTOP: '<i class="fas fa-fw fa-laptop"></i>',
            DEVICE_DESKTOP: '<i class="fas fa-fw fa-desktop"></i>',
            PREVIEW_ON: '<i class="fas fa-fw fa-eye"></i>',
            PREVIEW_OFF: '<i class="fas fa-fw fa-eye-slash"></i>',
            FULLSCREEN_ON: '<i class="fas fa-fw fa-compress"></i>',
            FULLSCREEN_OFF: '<i class="fas fa-fw fa-expand"></i>',
            SAVE: '<button class="btn btn-success aora-update-btn disable-btn">' + window.jsLang('data_update') + '</button>',
            ADD: '<i class="fas fa-plus"></i>'
        }, g = function (t, n) {
            var e = this.options, o = "";
            switch (t) {
                case P.CONTAINER:
                case P.SUB_CONTAINER:
                    return n && (o = '<a href="javascript:void(0);" class="'.concat(c.UI, " ").concat(c.CONTAINER_SETTING, '" title="').concat(e.locale.setting, '">').concat(D.SETTING_CONTAINER, "</a>")), '\n                <div class="'.concat(c.UI, " ").concat(c.TOOLBAR, " ").concat(c.TOOLBAR_CONTAINER, " ").concat(t === P.SUB_CONTAINER ? c.TOOLBAR_SUB_CONTAINER : "", '">\n                    <a href="javascript:void(0);" class="').concat(c.UI, " ").concat(c.CONTAINER_MOVE, '" title="').concat(e.locale.move, '">').concat(D.MOVE_CONTAINER, '</a>\n                    <a href="javascript:void(0);" class="').concat(c.UI, " ").concat(c.CONTAINER_MOVE_UP, '" title="').concat(e.locale.moveUp, '">').concat(D.MOVE_UP_CONTAINER, '</a>\n                    <a href="javascript:void(0);" class="').concat(c.UI, " ").concat(c.CONTAINER_MOVE_DOWN, '" title="').concat(e.locale.moveDown, '">').concat(D.MOVE_DOWN_CONTAINER, "</a>\n                    ").concat(o, '\n                    <a href="javascript:void(0);" class="').concat(c.UI, " ").concat(c.CONTAINER_CUT, '" title="').concat(e.locale.cut, '">').concat(D.CUT_CONTAINER, '</a>\n                    <a href="javascript:void(0);" class="').concat(c.UI, " ").concat(c.CONTAINER_COPY, '" title="').concat(e.locale.copy, '">').concat(D.COPY_CONTAINER, '</a>\n                    <a href="javascript:void(0);" class="').concat(c.UI, " ").concat(c.CONTAINER_DELETE, '" title="').concat(e.locale.delete, '">').concat(D.DELETE_CONTAINER, "</a>\n                </div>\n            ");
                case P.COMPONENT:
                    return n && (o = '<a href="javascript:void(0);" class="'.concat(c.UI, " ").concat(c.COMPONENT_SETTING, '" title="').concat(e.locale.setting, '">').concat(D.SETTING_COMPONENT, "</a>")), '\n                <div class="'.concat(c.UI, " ").concat(c.TOOLBAR, " ").concat(c.TOOLBAR_COMPONENT, '">\n                    <a href="javascript:void(0);" class="').concat(c.UI, " ").concat(c.COMPONENT_MOVE, '" title="').concat(e.locale.move, '">').concat(D.MOVE_COMPONENT, '</a>\n                    <a href="javascript:void(0);" class="').concat(c.UI, " ").concat(c.COMPONENT_MOVE_UP, '" title="').concat(e.locale.moveUp, '">').concat(D.MOVE_UP_COMPONENT, '</a>\n                    <a href="javascript:void(0);" class="').concat(c.UI, " ").concat(c.COMPONENT_MOVE_DOWN, '" title="').concat(e.locale.moveDown, '">').concat(D.MOVE_DOWN_COMPONENT, "</a>\n                    ").concat(o, '\n                    <a href="javascript:void(0);" class="').concat(c.UI, " ").concat(c.COMPONENT_CUT, '" title="').concat(e.locale.cut, '">').concat(D.CUT_COMPONENT, '</a>\n                    <a href="javascript:void(0);" class="').concat(c.UI, " ").concat(c.COMPONENT_COPY, '" title="').concat(e.locale.copy, '">').concat(D.COPY_COMPONENT, '</a>\n                    <a href="javascript:void(0);" class="').concat(c.UI, " ").concat(c.COMPONENT_DELETE, '" title="').concat(e.locale.delete, '">').concat(D.DELETE_COMPONENT, "</a>\n                </div>\n            ");
                case P.CONTENT_AREA:
                    return '\n                <div class="'.concat(c.UI, " ").concat(c.TOOLBAR_CONTENT_AREA, '">\n                    <a href="javascript:void(0)" class="').concat(c.UI, " ").concat(c.BTN, " ").concat(c.BTN_DEFAULT, " ").concat(c.ADD_CONTENT, '" title="').concat(e.locale.addContent, '">').concat(D.ADD_CONTENT, '</a>\n                    <a href="javascript:void(0)" class="').concat(c.UI, " ").concat(c.BTN, " ").concat(c.BTN_DEFAULT, " ").concat(c.PASTE_CONTENT, '" title="').concat(e.locale.pasteContent, '">').concat(D.PASTE_CONTENT, "</a>\n                </div>\n            ");
                case P.CONTAINER_CONTENT:
                case P.SUB_CONTAINER_CONTENT:
                    return '\n                <div class="'.concat(c.UI, " ").concat(c.TOOLBAR_CONTAINER_CONTENT, " ").concat(t === P.SUB_CONTAINER_CONTENT ? c.TOOLBAR_SUB_CONTAINER_CONTENT : "", '">\n                    <a href="javascript:void(0)" class="').concat(c.UI, " ").concat(c.BTN, " ").concat(c.BTN_DEFAULT, " ").concat(c.ADD_CONTENT, '" title="').concat(e.locale.addContent, '">').concat(D.ADD_CONTENT, '</a>\n                    <a href="javascript:void(0)" class="').concat(c.UI, " ").concat(c.BTN, " ").concat(c.BTN_DEFAULT, " ").concat(c.PASTE_CONTENT, '" title="').concat(e.locale.pasteContent, '">').concat(D.PASTE_CONTENT, "</a>\n                </div>\n            ");
                case P.CONTAINER_BOTTOM:
                case P.SUB_CONTAINER_BOTTOM:
                    return '\n                <div class="'.concat(c.UI, " ").concat(c.TOOLBAR, " ").concat(c.TOOLBAR_BOTTOM, " ").concat(c.TOOLBAR_CONTAINER_BOTTOM, " ").concat(t === P.SUB_CONTAINER_BOTTOM ? c.TOOLBAR_SUB_CONTAINER_BOTTOM : "", '">\n                    <a href="javascript:void(0)" class="').concat(c.UI, " ").concat(c.ADD_CONTENT, '" title="').concat(e.locale.addContentBelow, '">').concat(D.ADD_CONTENT, '</a>\n                    <a href="javascript:void(0)" class="').concat(c.UI, " ").concat(c.PASTE_CONTENT, '" title="').concat(e.locale.pasteContentBelow, '">').concat(D.PASTE_CONTENT, "</a>\n                </div>\n            ");
                case P.COMPONENT_BOTTOM:
                    return '\n                <div class="'.concat(c.UI, " ").concat(c.TOOLBAR, " ").concat(c.TOOLBAR_BOTTOM, " ").concat(c.TOOLBAR_COMPONENT_BOTTOM, '">\n                    <a href="javascript:void(0)" class="').concat(c.UI, " ").concat(c.ADD_CONTENT, '" title="').concat(e.locale.addContentBelow, '">').concat(D.ADD_CONTENT, '</a>\n                    <a href="javascript:void(0)" class="').concat(c.UI, " ").concat(c.PASTE_CONTENT, '" title="').concat(e.locale.pasteContentBelow, '">').concat(D.PASTE_CONTENT, "</a>\n                </div>\n            ")
            }
        }, B = function (t) {
            var n = this.options, e = (t.attr("data-type") || "").replace("component-", "");
            return e && e in aoraEditor.components || ("string" == typeof n.defaultComponentType ? e = n.defaultComponentType : "function" == typeof n.defaultComponentType && (e = n.defaultComponentType.call(this, t)), e || this.error("Component type is undefined!")), e
        }, M = function (t) {
            var n = this, e = n.options, o = t.closest('[data-type^="component"]'),
                a = t.closest(".".concat(c.CONTENT_AREA));
            !t.attr("id") && t.attr("id", s()), "function" == typeof e.onBeforeDynamicContentLoad && e.onBeforeDynamicContentLoad.call(n, t, o, a);
            var i = t.attr("data-dynamic-href"), r = T(o, ["data-type", "data-dynamic-href"], !1);
            // let hasPagination = r["data-pagination"] ?? false;
            // let page = 1;

            return r = $.param(r), $.ajax({
                url: i, data: r, type: "GET", dataType: "HTML", success: function (o, c, i) {
                    t.html(o), "function" == typeof e.onDynamicContentLoaded && e.onDynamicContentLoaded.call(n, t, i, a)
                }, error: function (o, c, i) {
                    "function" == typeof e.onDynamicContentError && e.onDynamicContentError.call(n, t, o, a)
                }
            })
        }, b = function (t) {
            var n = this, e = n.options, o = t.closest(".".concat(c.CONTAINER)),
                a = o.closest(".".concat(c.CONTENT_AREA));
            if (!t.hasClass(c.STATE_INITIALIZED) || !t.hasClass(c.STATE_INITIALIZING)) {
                t.addClass(c.STATE_INITIALIZING), t.attr("id", s()), "function" == typeof e.onBeforeInitComponent && e.onBeforeInitComponent.call(n, t, a), t.children(".".concat(c.COMPONENT_CONTENT)).attr("id", s());
                var i = B.call(n, t), r = aoraEditor.components[i];
                t.append(g.call(n, P.COMPONENT, r.settingEnabled)), t.append(g.call(n, P.COMPONENT_BOTTOM)), t.find("[data-dynamic-href]").each((function () {
                    var t = $(this);
                    M.call(n, t)
                })), "function" == typeof r.init && r.init.call(r, a, o, t, n), "function" == typeof e.onInitComponent && e.onInitComponent.call(n, t, a), t.addClass(c.STATE_INITIALIZED), t.removeClass(c.STATE_INITIALIZING)
            }
        }, L = function (t, n) {
            if (!t.is(".".concat(c.TOOLBAR_CONTAINER_CONTENT))) {
                var e, o = T.call(this, t, null, !0);
                t.wrap('<section class="'.concat(c.UI, " ").concat(c.COMPONENT, '" data-type="').concat(t.attr("data-type"), '" ').concat(o.join(" "), "></section>")), t.wrap('<section class="'.concat(c.UI, " ").concat(c.COMPONENT_CONTENT, '"></section>')), e = t.parent().parent(), t.removeAttr("data-type"), n && e.addClass("".concat(c.COMPONENT_EXISTING)), b.call(this, e)
            }
        }, k = function (t) {
            t.css("display", "block"), $(document.body).addClass(c.STATE_MODAL_OPENED), setTimeout((function () {
                t.addClass(c.STATE_SHOWED)
            }), 0)
        }, U = function (t, n, e, o) {
            var a = this.modal;
            this.modalTarget = t, this.modalTargetAction = n, e && a.addClass(c.MODAL_COMPONENT), o && a.addClass(c.MODAL_CONTAINER), a.css("display", "block"), k.call(this, a)
        }, w = function (t, n, e, o) {
            var a = this, i = a.options, r = a.contentAreasWrapper;
            e.addClass(c.CONTAINER_CONTENT), o && e.addClass(c.SUB_CONTAINER_CONTENT), e.attr("id", s());
            var l = $('<div class="'.concat(c.CONTAINER_CONTENT_INNER, '"></div>'));
            l.html(e.html()), e.html(l);
            var T = $(g.call(a, o ? P.SUB_CONTAINER_CONTENT : P.CONTAINER_CONTENT, i.containerSettingEnabled));
            T.appendTo(e), T.children(".".concat(c.ADD_CONTENT)).on("click", (function (t) {
                t.preventDefault(), U.call(a, l, y.APPEND, !0, !o)
            })), l.sortable({
                handle: ".".concat(c.COMPONENT_MOVE, ", .").concat(c.CONTAINER_MOVE),
                helper: "clone",
                items: "> .".concat(c.COMPONENT),
                connectWith: ".".concat(c.CONTAINER_CONTENT_INNER),
                tolerance: "pointer",
                receive: function (n, e) {
                    var o, l = e.helper, s = e.item;
                    l && l.remove(), (o = s.closest(".".concat(c.CONTAINER))).hasClass(c.STATE_TOOLBAR_SHOWED) || (r.find(".".concat(c.CONTAINER, ".").concat(c.STATE_TOOLBAR_SHOWED)).removeClass(c.STATE_TOOLBAR_SHOWED), o.addClass(c.STATE_TOOLBAR_SHOWED)), "function" == typeof i.onContainerChanged && i.onContainerChanged.call(a, n, o, t), "function" == typeof i.onContentChanged && i.onContentChanged.call(a, n, t), s.removeClass(c.UI_DRAGGING)
                },
                start: function (t, n) {
                    n.item.addClass(c.UI_DRAGGING), n.item.addClass(c.STATE_TOOLBAR_SHOWED)
                },
                stop: function (t, n) {
                    n.helper && n.helper.remove(), n.item.removeClass(c.UI_DRAGGING)
                }
            }), l.children().each((function () {
                var t = $(this);
                t.find('[data-type="container-content"]').length > 0 ? V.call(a, t) : L.call(a, t, !0)
            }))
        }, F = function (t) {
            var n = this, e = n.options, o = t.find('.row > [class*="col-"]');
            o.length > 0 && o.resizable({
                handles: "resizer, none", create: function () {
                    $(this).find(".".concat(c.UI_RESIZER)).attr("title", e.locale.columnResizeTitle)
                }, resize: function (t, o) {
                    var a = $(this), c = e.bootstrap.deviceClass[n.deviceMode],
                        i = "col-".concat(c, "-1 col-").concat(c, "-2 col-").concat(c, "-3 col-").concat(c, "-4 col-").concat(c, "-5 col-").concat(c, "-6 col-").concat(c, "-7 col-").concat(c, "-8 col-").concat(c, "-9 col-").concat(c, "-10 col-").concat(c, "-11 col-").concat(c, "-12"),
                        r = a.parent(), l = function (t, n) {
                            for (var e, o, a = 0; a < t.length; ++a) {
                                var c = Math.abs(t[a].width - n);
                                if (o && !(c < o)) return t[e].col;
                                e = a, o = c
                            }
                            return null
                        }(e.bootstrap.gridSystem, 100 * o.size.width / r.innerWidth());
                    a.removeClass(i).addClass("col-".concat(c, "-").concat(l)), a.css("width", ""), a.css("height", "")
                }
            })
        }, W = function (t) {
            var n = this, e = n.options, o = t.closest('[data-type="container-content"]').length > 0,
                a = t.closest(".".concat(c.CONTENT_AREA));
            t.hasClass(c.STATE_INITIALIZED) && t.hasClass(c.STATE_INITIALIZING) || (t.addClass(c.STATE_INITIALIZING), "function" == typeof e.onBeforeInitContainer && e.onBeforeInitContainer.call(n, t, a), o && t.addClass(c.SUB_CONTAINER), t.append(g.call(n, o ? P.SUB_CONTAINER : P.CONTAINER, e.containerSettingEnabled) + g.call(n, o ? P.SUB_CONTAINER_BOTTOM : P.CONTAINER_BOTTOM)), t.attr("id", s()), t.find('[data-type="container-content"]').each((function () {
                var e = $(this);
                !o && e.parents('[data-type="container-content"]').length > 0 || w.call(n, a, t, e, o)
            })), e.bootstrap.columnResizeEnabled && F.call(n, t), "function" == typeof e.onInitContainer && e.onInitContainer.call(n, t, a), t.addClass(c.STATE_INITIALIZED), t.removeClass(c.STATE_INITIALIZING))
        }, V = function (t) {
            var n;
            t.wrap('<section class="'.concat(c.UI, " ").concat(c.CONTAINER, '"></section>')), t.wrap('<section class="'.concat(c.UI, " ").concat(c.CONTAINER_INNER, '"></section>')), n = t.parent().parent(), W.call(this, n)
        }, G = function (t, n, e, o) {
            var a, i, r = this.modal, l = this.options, s = e.closest(".".concat(c.CONTENT_AREA)),
                E = n.attr("data-type"), d = r.find(n.attr("data-snippet")), O = d.html(),
                N = r.hasClass(c.MODAL_COMPONENT), p = r.hasClass(c.MODAL_CONTAINER), f = !1, C = !1, u = !1;
            if ("container" === E ? f = !0 : (N && !p && (C = !0), N && p && (e.is(".".concat(c.CONTAINER_CONTENT_INNER)) ? C = !0 : o === y.APPEND ? u = !0 : C = !0)), this.contentAreasWrapper.find(".".concat(c.STATE_TOOLBAR_SHOWED)).removeClass(c.STATE_TOOLBAR_SHOWED), f && (a = $(O), e[o](a), "function" == typeof l.onContainerSnippetAdded && l.onContainerSnippetAdded.call(this, t, a, n, s), "function" == typeof l.onContentChanged && l.onContentChanged.call(this, t, s), V.call(this, a), a.trigger("click")), C || u) {
                var A = T.call(this, d, null, !0);
                i = $('\n            <div data-type="'.concat(E, '" ').concat(A.join(" "), ">\n                ").concat(O, "\n            </div>\n        "))
            }
            C && (e[o](i), e.closest(".".concat(c.CONTAINER)).addClass(c.STATE_TOOLBAR_SHOWED), "function" == typeof l.onComponentSnippetAdded && l.onComponentSnippetAdded.call(this, t, i, n, s), "function" == typeof l.onContentChanged && l.onContentChanged.call(this, t, s), L.call(this, i), i.trigger("click"));
            u && ((a = $(l.containerForQuickAddComponent)).find('[data-type="container-content"]').eq(0).html(i), e[o](a), "function" == typeof l.onComponentSnippetAdded && l.onComponentSnippetAdded.call(this, t, i, n, s), "function" == typeof l.onContentChanged && l.onContentChanged.call(this, t, s), V.call(this, a), i.trigger("click"))
        }, j = function (t) {
            var n = !(arguments.length > 1 && void 0 !== arguments[1]) || arguments[1],
                e = arguments.length > 2 && void 0 !== arguments[2] && arguments[2], o = this,
                a = '<div class="'.concat(c.MODAL_FOOTER, '"></div>'),
                i = $('\n        <div class="'.concat(c.UI, " ").concat(c.MODAL, " ").concat(n ? c.STATE_HAS_FOOTER : "", '" id="').concat(t, '">\n            <div class="').concat(c.MODAL_HEADER, '">\n                <button type="button" class="').concat(c.MODAL_CLOSE, '">&times;</button>\n                <h4 class="').concat(c.MODAL_TITLE, '"></h4>\n            </div>\n            <div class="').concat(c.MODAL_BODY, '"></div>  <div class="').concat(c.MODAL_FOOTER, '"></div>\n            ').concat(n ? a : "", "\n        </div>\n    "));
            return e || i.on("click", ".".concat(c.MODAL_CLOSE), (function (t) {
                t.preventDefault(), R.call(o, i)
            })), i.appendTo(o.wrapper)
        }, H = function () {
            var t = this, n = t.options, e = t.modal = j.call(t, s(), !1, !0);
            "string" == typeof n.snippetsUrl && n.snippetsUrl.length > 0 ? (e.find(".".concat(c.MODAL_BODY)).append('\n            <div class="'.concat(c.SNIPPETS_WRAPPER, '">\n                <div class="').concat(c.SNIPPETS, '"></div>\n            </div>\n        ')), $.ajax({
                type: "get", dataType: "html", url: n.snippetsUrl, success: function (e) {
                    "function" == typeof n.onSnippetsLoaded && (e = n.onSnippetsLoaded.call(t, e) || e), h.call(t, e), v.call(t)
                }, error: function (e) {
                    "function" == typeof n.onSnippetsError && n.onSnippetsError.call(t, e)
                }
            }), S.call(t), e.find(".".concat(c.MODAL_CLOSE)).on("click", (function (n) {
                n.preventDefault(), m.call(t)
            })), e.on("click", ".".concat(c.SNIPPET), (function (n) {
                n.preventDefault();
                var e = $(this);
                G.call(t, n, e, t.modalTarget, t.modalTargetAction), m.call(t)
            }))) : t.error('"snippetsUrl" must be not null!')
        }, x = {COMPONENT: "component", CONTAINER: "container", EXTRA: "extra"}, Y = function () {
            r("closeSidebar");
            var t = this.options, n = this.sidebar, e = this.sidebarBody.children(".".concat(c.STATE_ACTIVE));
            if (e.length > 0) {
                switch (e.attr("[data-setting-category]")) {
                    case x.CONTAINER:
                        "function" == typeof t.containerSettingHideFunction && t.containerSettingHideFunction.call(this, e, this);
                        break;
                    case x.COMPONENT:
                        var o = e.attr("data-type"), a = aoraEditor.components[o];
                        "function" == typeof a.hideSettingForm && a.hideSettingForm.call(a, e, this)
                }
                e.removeClass(c.STATE_ACTIVE)
            }
            this.settingComponent = null, this.settingContainer = null, n.removeClass(c.STATE_OPENED), this.iframeBody.removeClass(c.STATE_SIDEBAR_SHOWED)
        }, K = function (t, n, e, o, a, i) {
            var r = this.sidebarBody, l = !1,
                s = r.children(".".concat(c.SETTING_FORM, '[data-setting-type="').concat(n, '"][data-setting-category="').concat(e, '"]'));
            if (0 === s.length) {
                if ("function" == typeof o) {
                    s = $('\n                <div\n                    data-setting-type="'.concat(n, '"\n                    data-setting-category="').concat(e, '"\n                    class="').concat(c.UI, " ").concat(c.SETTING_FORM, '"\n                ></div>\n            '));
                    var T = $('<span class="'.concat(c.SETTING_FORM_LOADING, '" />')).html("Loading...");
                    r.append(s), s.append(T), $.when(o.call(a, s, this)).done((function () {
                        setTimeout((function () {
                            T.remove(), "function" == typeof i && i(!1)
                        }), 100)
                    }))
                }
            } else l = !0;
            return {settingForm: s, isExisting: l}
        }, z = function (t, n, e, o, a, i, r) {
            var l = this, s = l.sidebar, T = l.sidebarTitle, E = l.sidebarBody,
                d = K.call(l, t, n, e, a, r, (function () {
                    "function" == typeof i && i.call(r, O, t, l)
                })), O = d.settingForm, N = d.isExisting,
                p = O.hasClass(c.STATE_ACTIVE) && (t.is(l.settingContainer) || t.is(l.settingComponent) || t.is("[data-extra-setting]"));
            switch (l.settingComponent = null, l.settingContainer = null, e) {
                case x.COMPONENT:
                    l.settingComponent = t;
                    break;
                case x.CONTAINER:
                    l.settingContainer = t
            }
            if (N) {
                if (p) return void Y.call(l);
                "function" == typeof i && i.call(r, O, t, l)
            }
            T.html(o), E.children(".".concat(c.STATE_ACTIVE)).removeClass(c.STATE_ACTIVE), O.addClass(c.STATE_ACTIVE), s.addClass(c.STATE_OPENED), l.iframeBody.addClass(c.STATE_SIDEBAR_SHOWED)
        }, Z = function (t) {
            r("openSidebar", t);
            var n = this.options;
            if (t.is(".".concat(c.COMPONENT))) {
                var e = B.call(this, t), o = aoraEditor.components[e];
                z.call(this, t, e, x.COMPONENT, o.settingTitle, o.initSettingForm, o.showSettingForm, o)
            } else if (t.is(".".concat(c.CONTAINER))) z.call(this, t, null, x.CONTAINER, n.locale.containerSetting, n.containerSettingInitFunction, n.containerSettingShowFunction, this); else {
                var a = t.attr("data-extra-setting"), i = n.extraSettings[a];
                z.call(this, t, a, x.EXTRA, i.title, i.settingInitFunction, i.settingShowFunction, i)
            }
        };

        function q(t) {
            return (q = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (t) {
                return typeof t
            } : function (t) {
                return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
            })(t)
        }

        var Q = function () {
            var t = this, n = t.options;
            $.isPlainObject(n.extraSettings) && $.each(n.extraSettings, (function (n, e) {
                var o;
                switch (q(e.trigger)) {
                    case"function":
                        o = e.trigger.call(t, e);
                        break;
                    case"string":
                        o = $(e.trigger);
                        break;
                    default:
                        o = e.trigger
                }
                o.attr("data-extra-setting", n), o.on("click", (function (n) {
                    n.preventDefault(), Z.call(t, o)
                })), e.autoInit && K.call(t, o, n, x.EXTRA, e.settingInitFunction, e)
            }))
        }, X = function () {
            var t = this, n = s(),
                e = t.sidebar = $('\n        <div class="'.concat(c.UI, " ").concat(c.SIDEBAR, '" id="').concat(n, '">\n            <div class="').concat(c.UI, " ").concat(c.SIDEBAR_HEADER, '">\n                <span class="').concat(c.UI, " ").concat(c.SIDEBAR_TITLE, '"></span>\n                <a href="javascript:void(0);" class="').concat(c.UI, " ").concat(c.SIDEBAR_CLOSER, '">&times;</a>\n            </div>\n            <div class="').concat(c.UI, " ").concat(c.SIDEBAR_BODY, '"></div>\n        </div>\n    '));
            e.find(".".concat(c.SIDEBAR_CLOSER)).on("click", (function (n) {
                n.preventDefault(), Y.call(t)
            })), t.sidebarTitle = e.find(".".concat(c.SIDEBAR_TITLE)), (t.sidebarBody = e.find(".".concat(c.SIDEBAR_BODY))).on("submit", "form", (function (t) {
                return t.preventDefault(), !1
            })), e.appendTo(t.wrapper)
        }, J = {MOBILE: "MOBILE", TABLET: "TABLET", LAPTOP: "LAPTOP", DESKTOP: "DESKTOP"}, tt = function (t, n) {
            var e = this.options, o = this.topbarCenter, a = this.iframe.parent(), i = "", r = "";
            switch (o.find(".".concat(c.STATE_ACTIVE)).removeClass(c.STATE_ACTIVE), n.addClass(c.STATE_ACTIVE), t) {
                case J.MOBILE:
                    i = e.widthMobile;
                    break;
                case J.TABLET:
                    i = e.widthTablet;
                    break;
                case J.LAPTOP:
                    i = e.widthLaptop;
                    break;
                case J.DESKTOP:
                    r = e.minWidthDesktop
            }
            this.deviceMode = t, a.css("width", i), a.css("min-width", r)
        }, nt = function () {
            var t = this, n = t.options, e = t.topbarCenter,
                o = t.btnMobile = $('\n        <a href="javascript:void(0);" title="'.concat(n.locale.viewOnMobile, '" class="').concat(c.UI, " ").concat(c.TOPBAR_BUTTON, '">').concat(D.DEVICE_MOBILE, "</a>\n    "));
            o.on("click", (function (n) {
                n.preventDefault(), tt.call(t, J.MOBILE, o)
            }));
            var a = t.btnTablet = $('\n        <a href="javascript:void(0);" title="'.concat(n.locale.viewOnTablet, '" class="').concat(c.UI, " ").concat(c.TOPBAR_BUTTON, '">').concat(D.DEVICE_TABLET, "</a>\n    "));
            a.on("click", (function (n) {
                n.preventDefault(), tt.call(t, J.TABLET, a)
            }));
            var i = t.btnLaptop = $('\n        <a href="javascript:void(0);" title="'.concat(n.locale.viewOnLaptop, '" class="').concat(c.UI, " ").concat(c.TOPBAR_BUTTON, '">').concat(D.DEVICE_LAPTOP, "</a>\n    "));
            i.on("click", (function (n) {
                n.preventDefault(), tt.call(t, J.LAPTOP, i)
            }));
            var r = t.btnDesktop = $('\n        <a href="javascript:void(0);" title="'.concat(n.locale.viewOnDesktop, '" class="').concat(c.UI, " ").concat(c.TOPBAR_BUTTON, '">').concat(D.DEVICE_DESKTOP, "</a>\n    "));
            r.on("click", (function (n) {
                n.preventDefault(), tt.call(t, J.DESKTOP, r)
            })).trigger("click"), e.append(o), e.append(a), e.append(i), e.append(r)
        }, et = function (t) {
            var n, e = t.clone(), o = B.call(this, e), a = aoraEditor.components[o], i = T(e, null, !1),
                r = e.find(".".concat(c.IFRAME_COVER_WRAPPER));
            if (r.length > 0) {
                r.find(".".concat(c.IFRAME_COVER)).remove();
                var l = r.children("iframe");
                r.hasClass(c.IFRAME_COVER_WRAPPER_FAKE) ? l.unwrap() : r.removeClass(c.IFRAME_COVER_WRAPPER)
            }
            "function" == typeof a.getContent ? n = a.getContent.call(a, e, this) : n = e.children(".".concat(c.COMPONENT_CONTENT)).html();
            return e.html(n).find("[data-dynamic-href]").each((function () {
                $(this).html("")
            })), e.children().attr(i).attr("data-type", "component-".concat(o)), e.html()
        };

        function ot(t, n) {
            var e = this, o = t.children(".".concat(c.CONTAINER_INNER)).clone();
            return o.find("[data-type=container-content]").not(n ? "" : ".".concat(c.SUB_CONTAINER_CONTENT)).each((function () {
                var t = $(this);
                t.removeClass("".concat(c.CONTAINER_CONTENT, " ").concat(c.SUB_CONTAINER_CONTENT, " ").concat(c.SORTABLE, " ").concat(c.RESIZABLE)).removeAttr("id");
                var n = t.children(), o = "";
                n.children().each((function () {
                    var t = $(this);
                    t.is(".".concat(c.COMPONENT)) ? o += et.call(e, t) : t.is(".".concat(c.SUB_CONTAINER)) && (o += ot.call(e, t, !0))
                })), t.html(o)
            })), o.html()
        }

        var at = function (t) {
            var n = this, e = [];
            return n.contentAreasWrapper.find(".".concat(c.CONTENT_AREA_INNER)).each((function () {
                var t = "";
                $(this).children(".".concat(c.CONTAINER)).each((function () {
                    var e = $(this);
                    t += ot.call(n, e)
                })), e.push(t)
            })), t ? e : e.join("\n")
        }, ct = function () {
            var t = this, n = t.options,
                e = t.btnPreview = $('<a href="javascript:void(0);" title="'.concat(n.locale.previewOff, '" class="').concat(c.UI, " ").concat(c.TOPBAR_BUTTON, '">').concat(D.PREVIEW_OFF, "</a>"));
            t.previewArea = $('<div class="'.concat(c.PREVIEW_AREA, '"></div>')), t.contentAreasWrapper.after(t.previewArea), e.on("click", (function (o) {
                o.preventDefault();
                var a = !e.hasClass(c.STATE_ACTIVE);
                e.html(a ? D.PREVIEW_ON : D.PREVIEW_OFF), e[a ? "addClass" : "removeClass"](c.STATE_ACTIVE), e.attr("title", a ? n.locale.previewOn : n.locale.previewOff), t.iframeBody[a ? "addClass" : "removeClass"](c.STATE_PREVIEWING), t.previewArea.html(""), Y.call(t), a && t.previewArea.html(at.call(t)).find("[data-dynamic-href]").each((function () {
                    var n = $(this);
                    n.html("Loading..."), M.call(t, n)
                }))
            })), t.topbarRight.append(e)
        }, it = function (t) {
            var n;
            t ? (n = this.wrapper[0]).requestFullscreen ? n.requestFullscreen() : n.mozRequestFullScreen ? n.mozRequestFullScreen() : n.webkitRequestFullscreen ? n.webkitRequestFullscreen() : n.msRequestFullscreen && n.msRequestFullscreen() : document.fullscreenElement && (document.exitFullscreen ? document.exitFullscreen() : document.mozCancelFullScreen ? document.mozCancelFullScreen() : document.webkitExitFullscreen ? document.webkitExitFullscreen() : document.msExitFullscreen && document.msExitFullscreen())
        }, rt = function () {
            var t = this, n = t.options,
                e = t.btnFullscreen = $('<a href="javascript:void(0);" title="'.concat(n.locale.fullscreenOff, '" class="').concat(c.UI, " ").concat(c.TOPBAR_BUTTON, '">').concat(D.FULLSCREEN_OFF, "</a>"));
            e.on("click", (function (n) {
                n.preventDefault(), it.call(t, !document.fullscreenElement)
            })), document.addEventListener("fullscreenchange", (function () {
                var t = !!document.fullscreenElement;
                e.html(t ? D.FULLSCREEN_ON : D.FULLSCREEN_OFF), e.attr("title", t ? n.locale.fullscreenOn : n.locale.fullscreenOff)
            })), t.topbarRight.append(e)
        }, lt = function () {
            var t = this, n = t.options,
                e = t.btnSave = $('<a href="javascript:void(0);" title="'.concat(n.locale.save, '" class="').concat(c.UI, " ").concat(c.TOPBAR_BUTTON, '">').concat(D.SAVE, "</a>"));
            e.on("click", (function (e) {
                e.preventDefault();
                var o = at.call(t);
                n.onSave.call(t, o)
            })), t.topbarRight.append(e)
        }, st = function () {
            var t = this.options;
            ct.apply(this), rt.apply(this), "function" == typeof t.onSave && lt.apply(this)
        }, Tt = function () {
            var t = this.options, n = this.topbarRight;
            $.isPlainObject(t.extraTopbarItems) && $.each(t.extraTopbarItems, (function (t, e) {
                var o = $(e.html);
                o.addClass("aoraeditor-ui aoraeditor-topbar-btn aoraeditor-topbar-btn-".concat(t)), o.on("click", e.click), n.append(o)
            }))
        }, Et = function () {
            var t = s(), n = this.options;
            this.topbar = $('\n        <div class="'.concat(c.UI, " ").concat(c.TOPBAR, '" id="').concat(t, '">\n            <div class="').concat(c.UI, " ").concat(c.TOPBAR_LEFT, '">\n                <div class="').concat(c.UI, " ").concat(c.TOPBAR_TITLE, '" title="').concat(n.title, '">').concat(n.title, '</div>\n            </div>\n            <div class="').concat(c.UI, " ").concat(c.TOPBAR_CENTER, '">\n            </div>\n            <div class="').concat(c.UI, " ").concat(c.TOPBAR_RIGHT, '">\n            </div>\n        </div>\n    ')), this.topbarLeft = this.topbar.find(".".concat(c.TOPBAR_LEFT)), this.topbarCenter = this.topbar.find(".".concat(c.TOPBAR_CENTER)), this.topbarRight = this.topbar.find(".".concat(c.TOPBAR_RIGHT)), this.topbar.appendTo(this.wrapper), nt.call(this), st.call(this), Tt.call(this)
        }, dt = function (t, n) {
            var e = $(t.target), o = e.closest(n);
            return e.is(n) ? e : o.length > 0 ? o : null
        }, Ot = function () {
            var t = this, n = t.options, e = t.contentAreasWrapper;
            t.iframeBody.on("click", (function (o) {
                var a = dt(o, ".".concat(c.SIDEBAR)), i = dt(o, ".".concat(c.MODAL)),
                    r = dt(o, ".".concat(c.CONTAINER));
                if (r) {
                    if (!r.hasClass(c.STATE_TOOLBAR_SHOWED)) {
                        e.find(".".concat(c.STATE_TOOLBAR_SHOWED)).removeClass(c.STATE_TOOLBAR_SHOWED), r.addClass(c.STATE_TOOLBAR_SHOWED);
                        var l = r.parent();
                        "function" == typeof n.onContainerSelected && n.onContainerSelected.call(t, o, r, l)
                    }
                } else a || i || e.find(".".concat(c.STATE_TOOLBAR_SHOWED)).removeClass(c.STATE_TOOLBAR_SHOWED);
                var s = dt(o, ".".concat(c.COMPONENT));
                if (s) {
                    if (!s.hasClass(c.STATE_TOOLBAR_SHOWED)) {
                        e.find(".".concat(c.COMPONENT, ".").concat(c.STATE_TOOLBAR_SHOWED)).removeClass(c.STATE_TOOLBAR_SHOWED), s.addClass(c.STATE_TOOLBAR_SHOWED);
                        var T = s.parent();
                        "function" == typeof n.onComponentSelected && n.onComponentSelected.call(t, o, s, T)
                    }
                    if (dt(o, ".".concat(c.TOOLBAR_COMPONENT))) return;
                    if (n.clickComponentToShowSetting) s.find(".".concat(c.COMPONENT_SETTING)).length > 0 ? (t.settingComponent = null, Z.call(t, s)) : Y.call(t)
                } else a || e.find(".".concat(c.COMPONENT, ".").concat(c.STATE_TOOLBAR_SHOWED)).removeClass(c.STATE_TOOLBAR_SHOWED)
            }))
        }, Nt = {ESC: 27}, pt = function (t, n) {
            this.copyContent = n ? null : t, this.cutContent = n ? t : null, this.contentAreasWrapper.find(".".concat(c.UI_CUTTING)).removeClass(c.UI_CUTTING), this.iframeBody.removeClass("".concat(c.STATE_COPYING, " ").concat(c.STATE_COPYING_COMPONENT, " ").concat(c.STATE_COPYING_CONTAINER, " ").concat(c.STATE_COPYING_SUB_CONTAINER)), t && (n && t.addClass(c.UI_CUTTING), this.iframeBody.addClass(c.STATE_COPYING), t.hasClass(c.COMPONENT) && this.iframeBody.addClass(c.STATE_COPYING_COMPONENT), t.hasClass(c.CONTAINER) && this.iframeBody.addClass(t.hasClass(c.SUB_CONTAINER) ? c.STATE_COPYING_SUB_CONTAINER : c.STATE_COPYING_CONTAINER))
        }, ft = function () {
            var t = this;
            t.iframeDoc.on("keydown", (function (n) {
                switch (n.keyCode) {
                    case Nt.ESC:
                        pt.call(t, null), it.call(t, !1)
                }
            }))
        }, Ct = function () {
            var t = this;
            t.contentAreasWrapper.on("click", ".".concat(c.PASTE_CONTENT), (function (n) {
                n.preventDefault(), r('Click on ".'.concat(c.PASTE_CONTENT, '"'));
                var e, o = $(this), a = !!t.copyContent, i = a ? t.copyContent : t.cutContent,
                    l = i.hasClass(c.COMPONENT), s = i.hasClass(c.CONTAINER), T = i.hasClass(c.SUB_CONTAINER);
                e = a ? $(l ? et.call(t, i) : ot.call(t, i, T)) : i;
                var E = null, d = o.closest(".".concat(c.TOOLBAR_BOTTOM));
                d.length > 0 && (d.hasClass(c.TOOLBAR_CONTAINER_BOTTOM) && (r("Target is container"), E = o.closest(".".concat(c.CONTAINER))), d.hasClass(c.TOOLBAR_COMPONENT_BOTTOM) && (r("Target is component"), E = o.closest(".".concat(c.COMPONENT))));
                var O = o.closest(".".concat(c.TOOLBAR_CONTAINER_CONTENT));
                O.length > 0 && (r("Target is component"), E = O.siblings(".".concat(c.CONTAINER_CONTENT_INNER)));
                var N = o.closest(".".concat(c.TOOLBAR_CONTENT_AREA));
                N.length > 0 && (r("Target is content-area"), E = N.siblings(".".concat(c.CONTENT_AREA_INNER)));
                var p = null;
                p = d.length > 0 ? y.AFTER : y.APPEND, r("isCopy: ".concat(a, " | isComponent: ").concat(l, " | isContainer: ").concat(s, " | isSubContainer: ").concat(T, " | action: ").concat(p)), r("target: ", E), E[p](e), pt.call(t, null), a && (l && L.call(t, e), s && V.call(t, e))
            }))
        }, ut = function () {
            var t = this;
            t.contentAreasWrapper.on("click", ".".concat(c.TOOLBAR_CONTAINER_BOTTOM, " .").concat(c.ADD_CONTENT), (function (n) {
                n.preventDefault();
                var e = $(this).closest(".".concat(c.CONTAINER));
                U.call(t, e, y.AFTER, !0, !0)
            }))
        }, At = function () {
            var t = this;
            t.contentAreasWrapper.on("click", ".".concat(c.TOOLBAR_COMPONENT_BOTTOM, " .").concat(c.ADD_CONTENT), (function (n) {
                n.preventDefault();
                var e = $(this), o = e.closest(".".concat(c.COMPONENT)), a = e.closest(".".concat(c.CONTAINER));
                U.call(t, o, y.AFTER, !0, !a.hasClass(c.SUB_CONTAINER))
            }))
        }, ht = function (t) {
            var n = B.call(this, t), e = aoraEditor.components[n];
            "function" == typeof e.destroy && e.destroy.call(e, t, this), t.remove()
        }, _t = function () {
            var t = this, n = t.options;
            t.contentAreasWrapper.on("click", ".".concat(c.COMPONENT_DELETE), (function (e) {
                e.preventDefault();
                var o = $(this);
                if (confirm(n.locale.confirmDeleteComponentText)) {
                    var a = o.closest(".".concat(c.COMPONENT)), i = a.closest(".".concat(c.CONTAINER)),
                        r = a.closest(".".concat(c.CONTENT_AREA));
                    "function" == typeof n.onBeforeComponentDeleted && n.onBeforeComponentDeleted.call(t, e, a, r), a.is(t.settingComponent) && Y.call(t), ht.call(t, a), "function" == typeof n.onComponentDeleted && n.onComponentDeleted.call(t, e, a, r), "function" == typeof n.onContainerChanged && n.onContainerChanged.call(t, e, i, r), "function" == typeof n.onContentChanged && n.onContentChanged.call(t, e, r)
                }
            }))
        }, It = function () {
            var t = this, n = t.options;
            t.contentAreasWrapper.on("click", ".".concat(c.COMPONENT_DUPLICATE), (function (e) {
                e.preventDefault();
                var o = $(this).closest(".".concat(c.COMPONENT)), a = o.closest(".".concat(c.CONTAINER)),
                    i = a.parent(), r = $(et.call(t, o));
                o.after(r), L.call(t, r), "function" == typeof n.onComponentDuplicated && n.onComponentDuplicated.call(t, o, r, i), "function" == typeof n.onContainerChanged && n.onContainerChanged.call(t, e, a, i), "function" == typeof n.onContentChanged && n.onContentChanged.call(t, e, i)
            }))
        }, vt = function () {
            var t = this;
            t.contentAreasWrapper.on("click", ".".concat(c.COMPONENT_COPY), (function (n) {
                n.preventDefault();
                var e = $(this).closest(".".concat(c.COMPONENT));
                pt.call(t, e, !1)
            }))
        }, Rt = function () {
            var t = this;
            t.contentAreasWrapper.on("click", ".".concat(c.COMPONENT_CUT), (function (n) {
                n.preventDefault();
                var e = $(this).closest(".".concat(c.COMPONENT));
                pt.call(t, e, !0)
            }))
        }, mt = function () {
            var t = this;
            t.contentAreasWrapper.on("click", ".".concat(c.COMPONENT_SETTING), (function (n) {
                n.preventDefault();
                var e = $(this).closest(".".concat(c.COMPONENT));
                Z.call(t, e)
            }))
        }, St = function () {
            var t = this, n = t.options;
            t.contentAreasWrapper.on("click", ".".concat(c.COMPONENT_MOVE_DOWN), (function (e) {
                e.preventDefault();
                var o = $(this).closest(".".concat(c.COMPONENT)), a = o.next(".".concat(c.COMPONENT));
                if (a.length > 0) {
                    var i = o.closest(".".concat(c.CONTAINER)), r = o.closest(".".concat(c.CONTENT_AREA));
                    a.after(o), "function" == typeof n.onContainerChanged && n.onContainerChanged.call(t, e, i, r), "function" == typeof n.onContentChanged && n.onContentChanged.call(t, e, r)
                }
            }))
        }, yt = function () {
            var t = this, n = t.options;
            t.contentAreasWrapper.on("click", ".".concat(c.COMPONENT_MOVE_UP), (function (e) {
                e.preventDefault();
                var o = $(this).closest(".".concat(c.COMPONENT)), a = o.prev(".".concat(c.COMPONENT));
                if (a.length > 0) {
                    var i = o.closest(".".concat(c.CONTAINER)), r = o.closest(".".concat(c.CONTENT_AREA));
                    a.before(o), "function" == typeof n.onContainerChanged && n.onContainerChanged.call(t, e, i, r), "function" == typeof n.onContentChanged && n.onContentChanged.call(t, e, r)
                }
            }))
        }, Pt = function () {
            var t = this, n = t.options;
            t.contentAreasWrapper.on("click", ".".concat(c.CONTAINER_DELETE), (function (e) {
                e.preventDefault();
                var o = $(this);
                if (confirm(n.locale.confirmDeleteContainerText)) {
                    var a = o.closest(".".concat(c.CONTAINER)), i = a.find(".".concat(c.COMPONENT)),
                        r = a.closest(".".concat(c.CONTENT_AREA));
                    if ("function" == typeof n.onBeforeContainerDeleted && n.onBeforeContainerDeleted.call(t, e, a, r), t.settingComponent) t.settingComponent.closest(".".concat(c.CONTAINER)).is(a) && Y.call(t); else a.is(t.settingContainer) && Y.call(t);
                    i.length > 0 && i.each((function () {
                        ht.call(t, $(this))
                    })), a.remove(), "function" == typeof n.onContainerDeleted && n.onContainerDeleted.call(t, e, a, r), "function" == typeof n.onContentChanged && n.onContentChanged.call(t, e, r)
                }
            }))
        }, Dt = function () {
            var t = this, n = t.options;
            t.contentAreasWrapper.on("click", ".".concat(c.CONTAINER_DUPLICATE), (function (e) {
                e.preventDefault();
                var o = $(this), a = o.closest(".".concat(c.CONTAINER)), i = a.parent(),
                    r = $(ot.call(t, a, o.parent().hasClass(c.TOOLBAR_SUB_CONTAINER)));
                a.after(r), V.call(t, r), "function" == typeof n.onContainerDuplicated && n.onContainerDuplicated.call(t, a, r, i), "function" == typeof n.onContentChanged && n.onContentChanged.call(t, e, i)
            }))
        }, gt = function () {
            var t = this;
            t.contentAreasWrapper.on("click", ".".concat(c.CONTAINER_COPY), (function (n) {
                n.preventDefault();
                var e = $(this).closest(".".concat(c.CONTAINER));
                pt.call(t, e, !1)
            }))
        }, Bt = function () {
            var t = this;
            t.contentAreasWrapper.on("click", ".".concat(c.CONTAINER_CUT), (function (n) {
                n.preventDefault();
                var e = $(this).closest(".".concat(c.CONTAINER));
                pt.call(t, e, !0)
            }))
        }, Mt = function () {
            var t = this;
            t.contentAreasWrapper.on("click", ".".concat(c.CONTAINER_SETTING), (function (n) {
                n.preventDefault();
                var e = $(this).closest(".".concat(c.CONTAINER));
                Z.call(t, e)
            }))
        }, bt = function () {
            var t = this, n = t.options;
            t.contentAreasWrapper.on("click", ".".concat(c.CONTAINER_MOVE_DOWN), (function (e) {
                e.preventDefault();
                var o = $(this).closest(".".concat(c.CONTAINER)), a = o.next(".".concat(c.CONTAINER));
                if (a.length > 0) {
                    var i = o.parent();
                    a.after(o), "function" == typeof n.onContentChanged && n.onContentChanged.call(t, e, i)
                }
            }))
        }, Lt = function () {
            var t = this, n = t.options;
            t.contentAreasWrapper.on("click", ".".concat(c.CONTAINER_MOVE_UP), (function (e) {
                e.preventDefault();
                var o = $(this).closest(".".concat(c.CONTAINER)), a = o.prev(".".concat(c.CONTAINER));
                if (a.length > 0) {
                    var i = o.parent();
                    a.before(o), "function" == typeof n.onContentChanged && n.onContentChanged.call(t, e, i)
                }
            }))
        }, kt = function () {
            Ot.call(this), ft.call(this), Ct.call(this), At.call(this), ut.call(this), _t.call(this), It.call(this), vt.call(this), Rt.call(this), St.call(this), yt.call(this), mt.call(this), Pt.call(this), Dt.call(this), gt.call(this), Bt.call(this), bt.call(this), Lt.call(this), Mt.call(this)
        }, Ut = function (t, n) {
            var e = this, o = e.options;
            t.addClass(c.CONTENT_AREA);
            var a = t.html(), i = $('<div class="'.concat(c.CONTENT_AREA_INNER, '"></div>')).html(a);
            if (t.html(i), "function" == typeof o.onBeforeInitContentArea && o.onBeforeInitContentArea.call(e, t), !n) {
                var r = $(g.call(e, P.CONTENT_AREA));
                t.append(r), r.children(".".concat(c.ADD_CONTENT)).on("click", (function (t) {
                    t.preventDefault(), U.call(e, i, y.APPEND, !0, !0)
                }))
            }
            i.sortable({
                handle: ".".concat(c.TOOLBAR_CONTAINER, ":not(.").concat(c.TOOLBAR_SUB_CONTAINER, ") .").concat(c.CONTAINER_MOVE),
                items: "> .".concat(c.CONTAINER),
                helper: "clone",
                connectWith: ".".concat(c.CONTENT_AREA),
                axis: "y",
                tolerance: "pointer",
                receive: function (n, a) {
                    var i = a.helper, r = a.item;
                    i && i.remove(), Y.call(e), "function" == typeof o.onContentChanged && o.onContentChanged.call(e, n, t), r.addClass(c.UI_DRAGGING)
                },
                start: function (t, n) {
                    n.item.addClass(c.UI_DRAGGING), n.item.addClass(c.STATE_TOOLBAR_SHOWED)
                },
                stop: function (t, n) {
                    n.helper && n.helper.remove(), n.item.removeClass(c.UI_DRAGGING)
                }
            }), ("function" == typeof o.onInitContentArea ? o.onInitContentArea.call(e, t) : i.children()).each((function () {
                V.call(e, $(this))
            }))
        }, wt = function () {
            var t, n = this, e = n.options, o = n.contentAreasWrapper;
            if (e.contentAreasSelector && (t = o.find(e.contentAreasSelector)), !t || 0 === t.length) {
                var a = o.html();
                t = $("<div />").html(a), o.empty().append(t)
            }
            t.each((function () {
                var t = $(this);
                t.attr("id") || t.attr("id", s()), Ut.call(n, t)
            }))
        }, Ft = function () {
            var t = this.element, n = this.options, e = this.generateId(),
                o = this.wrapper = $('\n        <div id="'.concat(e, '" class="').concat(c.UI, " ").concat(c.WRAPPER, '">\n            <div class="').concat(c.UI, " ").concat(c.IFRAME_WRAPPER, '">\n                <div class="').concat(c.UI, " ").concat(c.IFRAME_WIDTH_SWITCHER, '">\n                    <iframe class="').concat(c.UI, " ").concat(c.IFRAME, '"></iframe>\n                </div>\n            </div>\n        </div>\n    '));
            t.addClass(c.UI_HIDDEN), t.after(o);
            var a = this.iframe = o.find(".".concat(c.IFRAME));
            this.iframeDoc = a.contents(), this.iframeDoc.get(0).open(), this.iframeDoc.get(0).close(), this.iframeWindow = a[0].contentWindow ? a[0].contentWindow : a[0].contentDocument.defaultView, this.iframeHead = this.iframeDoc.find("head"), this.iframeBody = this.iframeDoc.find("body");
            var i = "";
            $('[data-type="aoraeditor-style"]').each((function () {
                var t = $(this), n = t.attr("id") || "", e = t.attr("href") || t.attr("data-href") || "";
                i += e ? '<link rel="stylesheet" type="text/css" href="'.concat(e, '" ').concat(n, " />\n") : '<style type="text/css" '.concat(n, ">").concat(t.html(), "</style>\n")
            })), $.isArray(n.contentStyles) && $.each(n.contentStyles, (function (t, n) {
                var e = n.id || "";
                n.href ? i += '<link rel="stylesheet" type="text/css" href="'.concat(n.href, '" ').concat(e, " />\n") : i += '<style type="text/css" '.concat(e, ">").concat(n.content, "</style>\n")
            })), this.iframeHead.append(i), this.contentAreasWrapper = $(n.contentAreasWrapper || "<div />"), this.contentAreasWrapper.attr("class", "".concat(c.UI, " ").concat(c.CONTENT_AREAS_WRAPPER)), this.contentAreasWrapper.html(t.val() || t.html() || ""), this.contentAreasWrapper.attr("id") || this.contentAreasWrapper.attr("id", this.generateId()), this.iframeBody.append(this.contentAreasWrapper).addClass(c.IFRAME_BODY), "function" == typeof n.onInitIframe && n.onInitIframe.call(this, this.iframe, this.iframeHead, this.iframeBody), wt.call(this), kt.call(this)
        }, Wt = function (t, n) {
            this.element = t, this.options = $.extend({}, i, n), Ft.call(this), Et.call(this), X.call(this), H.call(this), Q.call(this), this.id = s(), aoraEditor.instances[this.id] = this, "function" == typeof this.options.onReady && this.options.onReady.call(this)
        }, $t = function () {
            var t = this.element, n = this.id, e = at.call(this, !1);
            this.wrapper.remove(), t.is("textarea") ? t.val(e) : t.html(e), t.removeClass(c.UI_HIDDEN), t.data("aoraeditor", null), delete aoraEditor.instances[n]
        }, Vt = function (t, n) {
            var e, o = this.contentAreasWrapper;
            n ? n.jquery || (e = o.find(n)) : e = o.children(".".concat(c.CONTENT_AREA)), 0 === e.length && l("Content area does not exist!"), e.html(t), Ut.call(this, e, !0)
        };

        function Gt(t, n) {
            for (var e = 0; e < n.length; e++) {
                var o = n[e];
                o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(t, o.key, o)
            }
        }

        function jt(t, n, e) {
            return n in t ? Object.defineProperty(t, n, {
                value: e, enumerable: !0, configurable: !0, writable: !0
            }) : t[n] = e, t
        }

        if (!a.a.fn.sortable) throw new Error("[ aoraEditor ] $.fn.sortable does not exist1. Please import $.fn.sortable into your document for continue using aoraEditor.");
        window.AORADEDITOR_DEBUG = !0;
        var Ht = function () {
            function t(n, e) {
                !function (t, n) {
                    if (!(t instanceof n)) throw new TypeError("Cannot call a class as a function")
                }(this, t), jt(this, "settingComponent", null), jt(this, "settingContainer", null), jt(this, "copyContent", null), jt(this, "cutContent", null), jt(this, "categoryComponent", []), jt(this, "categoryContainer", []), Wt.apply(this, [n, e])
            }

            var n, e, o;
            return n = t, o = [{
                key: "log", value: function () {
                    r.apply(void 0, arguments)
                }
            }, {
                key: "error", value: function (t) {
                    l(t)
                }
            }, {
                key: "init", value: function (n, e) {
                    return new t(n, e)
                }
            }, {
                key: "loadDynamicContent", value: function (t) {
                    var n = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {};
                    t.each((function () {
                        M.call({
                            options: {
                                onDynamicContentLoaded: n.onSuccess, onDynamicContentError: n.onError
                            }
                        }, a()(this))
                    }))
                }
            }], (e = [{
                key: "getSettingContainer", value: function () {
                    return this.settingContainer
                }
            }, {
                key: "getSettingComponent", value: function () {
                    return this.settingComponent
                }
            }, {
                key: "generateId", value: function () {
                    return s()
                }
            }, {
                key: "getDataAttributes", value: function (t, n, e) {
                    return T.apply(this, [t, n, e])
                }
            }, {
                key: "initIframeCover", value: function (t, n) {
                    E.apply(this, [t, n])
                }
            }, {
                key: "initModal", value: function (t, n, e) {
                    return j.call(this, t, n, e)
                }
            }, {
                key: "showModal", value: function (t) {
                    k.call(this, t)
                }
            }, {
                key: "hideModal", value: function (t) {
                    R.call(this, t)
                }
            }, {
                key: "getContent", value: function (t) {
                    return at.apply(this, [t])
                }
            }, {
                key: "setContent", value: function (t, n) {
                    Vt.apply(this, [t, n])
                }
            }, {
                key: "destroy", value: function () {
                    $t.apply(this)
                }
            }, {
                key: "addSnippet", value: function (t, n, e, o, a, c) {
                    C.apply(this, [t, n, e, o, a, c])
                }
            }, {
                key: "initDynamicContent", value: function (t) {
                    return M.apply(this, [t])
                }
            }]) && Gt(n.prototype, e), o && Gt(n, o), t
        }();
        jt(Ht, "DEFAULTS", i), jt(Ht, "debug", !1), jt(Ht, "version", "2.0.1"), jt(Ht, "instances", {}), jt(Ht, "components", {blank: {settingEnabled: !1}}), a.a.fn.aoraeditor = function (t) {
            var n = a()(this), e = n.data("aoraeditor");
            return "string" != typeof t ? (e || (e = Ht.init(n, t), n.data("aoraeditor", e)), e) : e && e[t] && "function" == typeof e[t] ? e[t].apply(e, Array.prototype.slice.call(arguments, 1)) : void 0
        }, window.aoraEditor = a.a.aoraeditor = a.a.fn.aoraeditor.constructor = Ht
    }, window.jsLang = function (key, replace) {
         let string =  key.replace('data_','').toUpperCase();
         return string.split("_").join(" ");

     }]);



}));



