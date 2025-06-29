(function() {
  "use strict";
  function normalizeComponent(scriptExports, render, staticRenderFns, functionalTemplate, injectStyles, scopeId, moduleIdentifier, shadowMode) {
    var options = typeof scriptExports === "function" ? scriptExports.options : scriptExports;
    if (render) {
      options.render = render;
      options.staticRenderFns = staticRenderFns;
      options._compiled = true;
    }
    {
      options._scopeId = "data-v-" + scopeId;
    }
    return {
      exports: scriptExports,
      options
    };
  }
  const _sfc_main = {
    props: ["headline"],
    data() {
      return { icons: [] };
    },
    created() {
      this.load();
    },
    methods: {
      async load() {
        this.icons = await this.$api.get("journey-journal/place-icons") || [];
      },
      add() {
        this.icons.push({ icon: "", label: "" });
      },
      remove(i) {
        this.icons.splice(i, 1);
      },
      async save() {
        await this.$api.post("journey-journal/place-icons", this.icons);
        this.$panel.notification.success("Saved");
      }
    }
  };
  var _sfc_render = function render() {
    var _vm = this, _c = _vm._self._c;
    return _c("k-inside", [_c("k-view", { staticClass: "jj-place-icons" }, [_c("k-header", [_vm._v("Place icons")]), _c("ul", _vm._l(_vm.icons, function(icon, i) {
      return _c("li", { key: i }, [_c("k-icon", { attrs: { "type": icon.icon } }), _c("input", { directives: [{ name: "model", rawName: "v-model", value: icon.icon, expression: "icon.icon" }], attrs: { "placeholder": "Icon" }, domProps: { "value": icon.icon }, on: { "input": function($event) {
        if ($event.target.composing) return;
        _vm.$set(icon, "icon", $event.target.value);
      } } }), _c("input", { directives: [{ name: "model", rawName: "v-model", value: icon.label, expression: "icon.label" }], attrs: { "placeholder": "Description" }, domProps: { "value": icon.label }, on: { "input": function($event) {
        if ($event.target.composing) return;
        _vm.$set(icon, "label", $event.target.value);
      } } }), _c("k-button", { attrs: { "icon": "trash", "theme": "negative", "size": "xs" }, on: { "click": function($event) {
        return _vm.remove(i);
      } } })], 1);
    }), 0), _c("k-button", { attrs: { "icon": "add" }, on: { "click": _vm.add } }, [_vm._v("New Icon")]), _c("k-button", { attrs: { "icon": "check", "theme": "positive" }, on: { "click": _vm.save } }, [_vm._v("Save")])], 1)], 1);
  };
  var _sfc_staticRenderFns = [];
  _sfc_render._withStripped = true;
  var __component__ = /* @__PURE__ */ normalizeComponent(
    _sfc_main,
    _sfc_render,
    _sfc_staticRenderFns,
    false,
    null,
    "095dcab8"
  );
  __component__.options.__file = "/home/cj/dev/JourneyJournal/src/components/PlaceIcons.vue";
  const PlaceIcons = __component__.exports;
  panel.plugin("road42/journey-journal", {
    components: {
      "journey-journal-place-icons": PlaceIcons
    }
  });
})();
