"use strict";
(self["webpackChunk"] = self["webpackChunk"] || []).push([["resources_js_src_Pages_Dashboard_js"],{

/***/ "./node_modules/@ant-design/icons-svg/es/asn/PlusOutlined.js":
/*!*******************************************************************!*\
  !*** ./node_modules/@ant-design/icons-svg/es/asn/PlusOutlined.js ***!
  \*******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
// This icon file is generated automatically.
var PlusOutlined = { "icon": { "tag": "svg", "attrs": { "viewBox": "64 64 896 896", "focusable": "false" }, "children": [{ "tag": "defs", "attrs": {}, "children": [{ "tag": "style", "attrs": {} }] }, { "tag": "path", "attrs": { "d": "M482 152h60q8 0 8 8v704q0 8-8 8h-60q-8 0-8-8V160q0-8 8-8z" } }, { "tag": "path", "attrs": { "d": "M176 474h672q8 0 8 8v60q0 8-8 8H176q-8 0-8-8v-60q0-8 8-8z" } }] }, "name": "plus", "theme": "outlined" };
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (PlusOutlined);


/***/ }),

/***/ "./node_modules/@ant-design/icons/es/icons/PlusOutlined.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@ant-design/icons/es/icons/PlusOutlined.js ***!
  \*****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_objectSpread2__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/objectSpread2 */ "./node_modules/@babel/runtime/helpers/esm/objectSpread2.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var _ant_design_icons_svg_es_asn_PlusOutlined__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @ant-design/icons-svg/es/asn/PlusOutlined */ "./node_modules/@ant-design/icons-svg/es/asn/PlusOutlined.js");
/* harmony import */ var _components_AntdIcon__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../components/AntdIcon */ "./node_modules/@ant-design/icons/es/components/AntdIcon.js");

// GENERATE BY ./scripts/generate.ts
// DON NOT EDIT IT MANUALLY




var PlusOutlined = function PlusOutlined(props, ref) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_1__.createElement(_components_AntdIcon__WEBPACK_IMPORTED_MODULE_2__["default"], (0,_babel_runtime_helpers_esm_objectSpread2__WEBPACK_IMPORTED_MODULE_0__["default"])((0,_babel_runtime_helpers_esm_objectSpread2__WEBPACK_IMPORTED_MODULE_0__["default"])({}, props), {}, {
    ref: ref,
    icon: _ant_design_icons_svg_es_asn_PlusOutlined__WEBPACK_IMPORTED_MODULE_3__["default"]
  }));
};

PlusOutlined.displayName = 'PlusOutlined';
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (/*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_1__.forwardRef(PlusOutlined));

/***/ }),

/***/ "./node_modules/antd/es/_util/responsiveObserve.js":
/*!*********************************************************!*\
  !*** ./node_modules/antd/es/_util/responsiveObserve.js ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "responsiveArray": () => (/* binding */ responsiveArray),
/* harmony export */   "responsiveMap": () => (/* binding */ responsiveMap),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/esm/extends */ "./node_modules/@babel/runtime/helpers/esm/extends.js");


var responsiveArray = ['xxl', 'xl', 'lg', 'md', 'sm', 'xs'];
var responsiveMap = {
  xs: '(max-width: 575px)',
  sm: '(min-width: 576px)',
  md: '(min-width: 768px)',
  lg: '(min-width: 992px)',
  xl: '(min-width: 1200px)',
  xxl: '(min-width: 1600px)'
};
var subscribers = new Map();
var subUid = -1;
var screens = {};
var responsiveObserve = {
  matchHandlers: {},
  dispatch: function dispatch(pointMap) {
    screens = pointMap;
    subscribers.forEach(function (func) {
      return func(screens);
    });
    return subscribers.size >= 1;
  },
  subscribe: function subscribe(func) {
    if (!subscribers.size) this.register();
    subUid += 1;
    subscribers.set(subUid, func);
    func(screens);
    return subUid;
  },
  unsubscribe: function unsubscribe(token) {
    subscribers["delete"](token);
    if (!subscribers.size) this.unregister();
  },
  unregister: function unregister() {
    var _this = this;

    Object.keys(responsiveMap).forEach(function (screen) {
      var matchMediaQuery = responsiveMap[screen];
      var handler = _this.matchHandlers[matchMediaQuery];
      handler === null || handler === void 0 ? void 0 : handler.mql.removeListener(handler === null || handler === void 0 ? void 0 : handler.listener);
    });
    subscribers.clear();
  },
  register: function register() {
    var _this2 = this;

    Object.keys(responsiveMap).forEach(function (screen) {
      var matchMediaQuery = responsiveMap[screen];

      var listener = function listener(_ref) {
        var matches = _ref.matches;

        _this2.dispatch((0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__["default"])((0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__["default"])({}, screens), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])({}, screen, matches)));
      };

      var mql = window.matchMedia(matchMediaQuery);
      mql.addListener(listener);
      _this2.matchHandlers[matchMediaQuery] = {
        mql: mql,
        listener: listener
      };
      listener(mql);
    });
  }
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (responsiveObserve);

/***/ }),

/***/ "./node_modules/antd/es/card/Grid.js":
/*!*******************************************!*\
  !*** ./node_modules/antd/es/card/Grid.js ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/extends */ "./node_modules/@babel/runtime/helpers/esm/extends.js");
/* harmony import */ var _babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/esm/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _config_provider__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../config-provider */ "./node_modules/antd/es/config-provider/context.js");



var __rest = undefined && undefined.__rest || function (s, e) {
  var t = {};

  for (var p in s) {
    if (Object.prototype.hasOwnProperty.call(s, p) && e.indexOf(p) < 0) t[p] = s[p];
  }

  if (s != null && typeof Object.getOwnPropertySymbols === "function") for (var i = 0, p = Object.getOwnPropertySymbols(s); i < p.length; i++) {
    if (e.indexOf(p[i]) < 0 && Object.prototype.propertyIsEnumerable.call(s, p[i])) t[p[i]] = s[p[i]];
  }
  return t;
};





var Grid = function Grid(_a) {
  var prefixCls = _a.prefixCls,
      className = _a.className,
      _a$hoverable = _a.hoverable,
      hoverable = _a$hoverable === void 0 ? true : _a$hoverable,
      props = __rest(_a, ["prefixCls", "className", "hoverable"]);

  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_config_provider__WEBPACK_IMPORTED_MODULE_4__.ConfigConsumer, null, function (_ref) {
    var getPrefixCls = _ref.getPrefixCls;
    var prefix = getPrefixCls('card', prefixCls);
    var classString = classnames__WEBPACK_IMPORTED_MODULE_3___default()("".concat(prefix, "-grid"), className, (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])({}, "".concat(prefix, "-grid-hoverable"), hoverable));
    return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement("div", (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({}, props, {
      className: classString
    }));
  });
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Grid);

/***/ }),

/***/ "./node_modules/antd/es/card/Meta.js":
/*!*******************************************!*\
  !*** ./node_modules/antd/es/card/Meta.js ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/extends */ "./node_modules/@babel/runtime/helpers/esm/extends.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _config_provider__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../config-provider */ "./node_modules/antd/es/config-provider/context.js");


var __rest = undefined && undefined.__rest || function (s, e) {
  var t = {};

  for (var p in s) {
    if (Object.prototype.hasOwnProperty.call(s, p) && e.indexOf(p) < 0) t[p] = s[p];
  }

  if (s != null && typeof Object.getOwnPropertySymbols === "function") for (var i = 0, p = Object.getOwnPropertySymbols(s); i < p.length; i++) {
    if (e.indexOf(p[i]) < 0 && Object.prototype.propertyIsEnumerable.call(s, p[i])) t[p[i]] = s[p[i]];
  }
  return t;
};





var Meta = function Meta(props) {
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_1__.createElement(_config_provider__WEBPACK_IMPORTED_MODULE_3__.ConfigConsumer, null, function (_ref) {
    var getPrefixCls = _ref.getPrefixCls;

    var customizePrefixCls = props.prefixCls,
        className = props.className,
        avatar = props.avatar,
        title = props.title,
        description = props.description,
        others = __rest(props, ["prefixCls", "className", "avatar", "title", "description"]);

    var prefixCls = getPrefixCls('card', customizePrefixCls);
    var classString = classnames__WEBPACK_IMPORTED_MODULE_2___default()("".concat(prefixCls, "-meta"), className);
    var avatarDom = avatar ? /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_1__.createElement("div", {
      className: "".concat(prefixCls, "-meta-avatar")
    }, avatar) : null;
    var titleDom = title ? /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_1__.createElement("div", {
      className: "".concat(prefixCls, "-meta-title")
    }, title) : null;
    var descriptionDom = description ? /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_1__.createElement("div", {
      className: "".concat(prefixCls, "-meta-description")
    }, description) : null;
    var MetaDetail = titleDom || descriptionDom ? /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_1__.createElement("div", {
      className: "".concat(prefixCls, "-meta-detail")
    }, titleDom, descriptionDom) : null;
    return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_1__.createElement("div", (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({}, others, {
      className: classString
    }), avatarDom, MetaDetail);
  });
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Meta);

/***/ }),

/***/ "./node_modules/antd/es/card/index.js":
/*!********************************************!*\
  !*** ./node_modules/antd/es/card/index.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/esm/extends */ "./node_modules/@babel/runtime/helpers/esm/extends.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var rc_util_es_omit__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! rc-util/es/omit */ "./node_modules/rc-util/es/omit.js");
/* harmony import */ var _Grid__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./Grid */ "./node_modules/antd/es/card/Grid.js");
/* harmony import */ var _Meta__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./Meta */ "./node_modules/antd/es/card/Meta.js");
/* harmony import */ var _tabs__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../tabs */ "./node_modules/antd/es/tabs/index.js");
/* harmony import */ var _row__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../row */ "./node_modules/antd/es/row/index.js");
/* harmony import */ var _col__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../col */ "./node_modules/antd/es/col/index.js");
/* harmony import */ var _config_provider__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../config-provider */ "./node_modules/antd/es/config-provider/context.js");
/* harmony import */ var _config_provider_SizeContext__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../config-provider/SizeContext */ "./node_modules/antd/es/config-provider/SizeContext.js");



var __rest = undefined && undefined.__rest || function (s, e) {
  var t = {};

  for (var p in s) {
    if (Object.prototype.hasOwnProperty.call(s, p) && e.indexOf(p) < 0) t[p] = s[p];
  }

  if (s != null && typeof Object.getOwnPropertySymbols === "function") for (var i = 0, p = Object.getOwnPropertySymbols(s); i < p.length; i++) {
    if (e.indexOf(p[i]) < 0 && Object.prototype.propertyIsEnumerable.call(s, p[i])) t[p[i]] = s[p[i]];
  }
  return t;
};












function getAction(actions) {
  var actionList = actions.map(function (action, index) {
    return (
      /*#__PURE__*/
      // eslint-disable-next-line react/no-array-index-key
      react__WEBPACK_IMPORTED_MODULE_2__.createElement("li", {
        style: {
          width: "".concat(100 / actions.length, "%")
        },
        key: "action-".concat(index)
      }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement("span", null, action))
    );
  });
  return actionList;
}

var Card = function Card(props) {
  var _extends2, _classNames;

  var _React$useContext = react__WEBPACK_IMPORTED_MODULE_2__.useContext(_config_provider__WEBPACK_IMPORTED_MODULE_5__.ConfigContext),
      getPrefixCls = _React$useContext.getPrefixCls,
      direction = _React$useContext.direction;

  var size = react__WEBPACK_IMPORTED_MODULE_2__.useContext(_config_provider_SizeContext__WEBPACK_IMPORTED_MODULE_6__["default"]);

  var onTabChange = function onTabChange(key) {
    var _a;

    (_a = props.onTabChange) === null || _a === void 0 ? void 0 : _a.call(props, key);
  };

  var isContainGrid = function isContainGrid() {
    var containGrid;
    react__WEBPACK_IMPORTED_MODULE_2__.Children.forEach(props.children, function (element) {
      if (element && element.type && element.type === _Grid__WEBPACK_IMPORTED_MODULE_7__["default"]) {
        containGrid = true;
      }
    });
    return containGrid;
  };

  var customizePrefixCls = props.prefixCls,
      className = props.className,
      extra = props.extra,
      _props$headStyle = props.headStyle,
      headStyle = _props$headStyle === void 0 ? {} : _props$headStyle,
      _props$bodyStyle = props.bodyStyle,
      bodyStyle = _props$bodyStyle === void 0 ? {} : _props$bodyStyle,
      title = props.title,
      loading = props.loading,
      _props$bordered = props.bordered,
      bordered = _props$bordered === void 0 ? true : _props$bordered,
      customizeSize = props.size,
      type = props.type,
      cover = props.cover,
      actions = props.actions,
      tabList = props.tabList,
      children = props.children,
      activeTabKey = props.activeTabKey,
      defaultActiveTabKey = props.defaultActiveTabKey,
      tabBarExtraContent = props.tabBarExtraContent,
      hoverable = props.hoverable,
      _props$tabProps = props.tabProps,
      tabProps = _props$tabProps === void 0 ? {} : _props$tabProps,
      others = __rest(props, ["prefixCls", "className", "extra", "headStyle", "bodyStyle", "title", "loading", "bordered", "size", "type", "cover", "actions", "tabList", "children", "activeTabKey", "defaultActiveTabKey", "tabBarExtraContent", "hoverable", "tabProps"]);

  var prefixCls = getPrefixCls('card', customizePrefixCls);
  var loadingBlockStyle = bodyStyle.padding === 0 || bodyStyle.padding === '0px' ? {
    padding: 24
  } : undefined;
  var block = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement("div", {
    className: "".concat(prefixCls, "-loading-block")
  });
  var loadingBlock = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement("div", {
    className: "".concat(prefixCls, "-loading-content"),
    style: loadingBlockStyle
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_row__WEBPACK_IMPORTED_MODULE_8__["default"], {
    gutter: 8
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_col__WEBPACK_IMPORTED_MODULE_9__["default"], {
    span: 22
  }, block)), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_row__WEBPACK_IMPORTED_MODULE_8__["default"], {
    gutter: 8
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_col__WEBPACK_IMPORTED_MODULE_9__["default"], {
    span: 8
  }, block), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_col__WEBPACK_IMPORTED_MODULE_9__["default"], {
    span: 15
  }, block)), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_row__WEBPACK_IMPORTED_MODULE_8__["default"], {
    gutter: 8
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_col__WEBPACK_IMPORTED_MODULE_9__["default"], {
    span: 6
  }, block), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_col__WEBPACK_IMPORTED_MODULE_9__["default"], {
    span: 18
  }, block)), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_row__WEBPACK_IMPORTED_MODULE_8__["default"], {
    gutter: 8
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_col__WEBPACK_IMPORTED_MODULE_9__["default"], {
    span: 13
  }, block), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_col__WEBPACK_IMPORTED_MODULE_9__["default"], {
    span: 9
  }, block)), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_row__WEBPACK_IMPORTED_MODULE_8__["default"], {
    gutter: 8
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_col__WEBPACK_IMPORTED_MODULE_9__["default"], {
    span: 4
  }, block), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_col__WEBPACK_IMPORTED_MODULE_9__["default"], {
    span: 3
  }, block), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_col__WEBPACK_IMPORTED_MODULE_9__["default"], {
    span: 16
  }, block)));
  var hasActiveTabKey = activeTabKey !== undefined;

  var extraProps = (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__["default"])((0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__["default"])({}, tabProps), (_extends2 = {}, (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_extends2, hasActiveTabKey ? 'activeKey' : 'defaultActiveKey', hasActiveTabKey ? activeTabKey : defaultActiveTabKey), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_extends2, "tabBarExtraContent", tabBarExtraContent), _extends2));

  var head;
  var tabs = tabList && tabList.length ? /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_tabs__WEBPACK_IMPORTED_MODULE_10__["default"], (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__["default"])({
    size: "large"
  }, extraProps, {
    className: "".concat(prefixCls, "-head-tabs"),
    onChange: onTabChange
  }), tabList.map(function (item) {
    return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_tabs__WEBPACK_IMPORTED_MODULE_10__["default"].TabPane, {
      tab: item.tab,
      disabled: item.disabled,
      key: item.key
    });
  })) : null;

  if (title || extra || tabs) {
    head = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement("div", {
      className: "".concat(prefixCls, "-head"),
      style: headStyle
    }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement("div", {
      className: "".concat(prefixCls, "-head-wrapper")
    }, title && /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement("div", {
      className: "".concat(prefixCls, "-head-title")
    }, title), extra && /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement("div", {
      className: "".concat(prefixCls, "-extra")
    }, extra)), tabs);
  }

  var coverDom = cover ? /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement("div", {
    className: "".concat(prefixCls, "-cover")
  }, cover) : null;
  var body = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement("div", {
    className: "".concat(prefixCls, "-body"),
    style: bodyStyle
  }, loading ? loadingBlock : children);
  var actionDom = actions && actions.length ? /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement("ul", {
    className: "".concat(prefixCls, "-actions")
  }, getAction(actions)) : null;
  var divProps = (0,rc_util_es_omit__WEBPACK_IMPORTED_MODULE_4__["default"])(others, ['onTabChange']);
  var mergedSize = customizeSize || size;
  var classString = classnames__WEBPACK_IMPORTED_MODULE_3___default()(prefixCls, (_classNames = {}, (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_classNames, "".concat(prefixCls, "-loading"), loading), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_classNames, "".concat(prefixCls, "-bordered"), bordered), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_classNames, "".concat(prefixCls, "-hoverable"), hoverable), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_classNames, "".concat(prefixCls, "-contain-grid"), isContainGrid()), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_classNames, "".concat(prefixCls, "-contain-tabs"), tabList && tabList.length), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_classNames, "".concat(prefixCls, "-").concat(mergedSize), mergedSize), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_classNames, "".concat(prefixCls, "-type-").concat(type), !!type), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_classNames, "".concat(prefixCls, "-rtl"), direction === 'rtl'), _classNames), className);
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement("div", (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__["default"])({}, divProps, {
    className: classString
  }), head, coverDom, body, actionDom);
};

Card.Grid = _Grid__WEBPACK_IMPORTED_MODULE_7__["default"];
Card.Meta = _Meta__WEBPACK_IMPORTED_MODULE_11__["default"];
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Card);

/***/ }),

/***/ "./node_modules/antd/es/col/index.js":
/*!*******************************************!*\
  !*** ./node_modules/antd/es/col/index.js ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _grid__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../grid */ "./node_modules/antd/es/grid/col.js");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_grid__WEBPACK_IMPORTED_MODULE_0__["default"]);

/***/ }),

/***/ "./node_modules/antd/es/grid/RowContext.js":
/*!*************************************************!*\
  !*** ./node_modules/antd/es/grid/RowContext.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");

var RowContext = /*#__PURE__*/(0,react__WEBPACK_IMPORTED_MODULE_0__.createContext)({});
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (RowContext);

/***/ }),

/***/ "./node_modules/antd/es/grid/col.js":
/*!******************************************!*\
  !*** ./node_modules/antd/es/grid/col.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/esm/extends */ "./node_modules/@babel/runtime/helpers/esm/extends.js");
/* harmony import */ var _babel_runtime_helpers_esm_typeof__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/esm/typeof */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _RowContext__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./RowContext */ "./node_modules/antd/es/grid/RowContext.js");
/* harmony import */ var _config_provider__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../config-provider */ "./node_modules/antd/es/config-provider/context.js");




var __rest = undefined && undefined.__rest || function (s, e) {
  var t = {};

  for (var p in s) {
    if (Object.prototype.hasOwnProperty.call(s, p) && e.indexOf(p) < 0) t[p] = s[p];
  }

  if (s != null && typeof Object.getOwnPropertySymbols === "function") for (var i = 0, p = Object.getOwnPropertySymbols(s); i < p.length; i++) {
    if (e.indexOf(p[i]) < 0 && Object.prototype.propertyIsEnumerable.call(s, p[i])) t[p[i]] = s[p[i]];
  }
  return t;
};






function parseFlex(flex) {
  if (typeof flex === 'number') {
    return "".concat(flex, " ").concat(flex, " auto");
  }

  if (/^\d+(\.\d+)?(px|em|rem|%)$/.test(flex)) {
    return "0 0 ".concat(flex);
  }

  return flex;
}

var sizes = ['xs', 'sm', 'md', 'lg', 'xl', 'xxl'];
var Col = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_3__.forwardRef(function (props, ref) {
  var _classNames;

  var _React$useContext = react__WEBPACK_IMPORTED_MODULE_3__.useContext(_config_provider__WEBPACK_IMPORTED_MODULE_5__.ConfigContext),
      getPrefixCls = _React$useContext.getPrefixCls,
      direction = _React$useContext.direction;

  var _React$useContext2 = react__WEBPACK_IMPORTED_MODULE_3__.useContext(_RowContext__WEBPACK_IMPORTED_MODULE_6__["default"]),
      gutter = _React$useContext2.gutter,
      wrap = _React$useContext2.wrap,
      supportFlexGap = _React$useContext2.supportFlexGap;

  var customizePrefixCls = props.prefixCls,
      span = props.span,
      order = props.order,
      offset = props.offset,
      push = props.push,
      pull = props.pull,
      className = props.className,
      children = props.children,
      flex = props.flex,
      style = props.style,
      others = __rest(props, ["prefixCls", "span", "order", "offset", "push", "pull", "className", "children", "flex", "style"]);

  var prefixCls = getPrefixCls('col', customizePrefixCls);
  var sizeClassObj = {};
  sizes.forEach(function (size) {
    var _extends2;

    var sizeProps = {};
    var propSize = props[size];

    if (typeof propSize === 'number') {
      sizeProps.span = propSize;
    } else if ((0,_babel_runtime_helpers_esm_typeof__WEBPACK_IMPORTED_MODULE_2__["default"])(propSize) === 'object') {
      sizeProps = propSize || {};
    }

    delete others[size];
    sizeClassObj = (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__["default"])((0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__["default"])({}, sizeClassObj), (_extends2 = {}, (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_extends2, "".concat(prefixCls, "-").concat(size, "-").concat(sizeProps.span), sizeProps.span !== undefined), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_extends2, "".concat(prefixCls, "-").concat(size, "-order-").concat(sizeProps.order), sizeProps.order || sizeProps.order === 0), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_extends2, "".concat(prefixCls, "-").concat(size, "-offset-").concat(sizeProps.offset), sizeProps.offset || sizeProps.offset === 0), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_extends2, "".concat(prefixCls, "-").concat(size, "-push-").concat(sizeProps.push), sizeProps.push || sizeProps.push === 0), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_extends2, "".concat(prefixCls, "-").concat(size, "-pull-").concat(sizeProps.pull), sizeProps.pull || sizeProps.pull === 0), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_extends2, "".concat(prefixCls, "-rtl"), direction === 'rtl'), _extends2));
  });
  var classes = classnames__WEBPACK_IMPORTED_MODULE_4___default()(prefixCls, (_classNames = {}, (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_classNames, "".concat(prefixCls, "-").concat(span), span !== undefined), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_classNames, "".concat(prefixCls, "-order-").concat(order), order), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_classNames, "".concat(prefixCls, "-offset-").concat(offset), offset), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_classNames, "".concat(prefixCls, "-push-").concat(push), push), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_classNames, "".concat(prefixCls, "-pull-").concat(pull), pull), _classNames), className, sizeClassObj);
  var mergedStyle = {}; // Horizontal gutter use padding

  if (gutter && gutter[0] > 0) {
    var horizontalGutter = gutter[0] / 2;
    mergedStyle.paddingLeft = horizontalGutter;
    mergedStyle.paddingRight = horizontalGutter;
  } // Vertical gutter use padding when gap not support


  if (gutter && gutter[1] > 0 && !supportFlexGap) {
    var verticalGutter = gutter[1] / 2;
    mergedStyle.paddingTop = verticalGutter;
    mergedStyle.paddingBottom = verticalGutter;
  }

  if (flex) {
    mergedStyle.flex = parseFlex(flex); // Hack for Firefox to avoid size issue
    // https://github.com/ant-design/ant-design/pull/20023#issuecomment-564389553

    if (flex === 'auto' && wrap === false && !mergedStyle.minWidth) {
      mergedStyle.minWidth = 0;
    }
  }

  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_3__.createElement("div", (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__["default"])({}, others, {
    style: (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__["default"])((0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_1__["default"])({}, mergedStyle), style),
    className: classes,
    ref: ref
  }), children);
});
Col.displayName = 'Col';
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Col);

/***/ }),

/***/ "./node_modules/antd/es/grid/row.js":
/*!******************************************!*\
  !*** ./node_modules/antd/es/grid/row.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/extends */ "./node_modules/@babel/runtime/helpers/esm/extends.js");
/* harmony import */ var _babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/esm/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_esm_typeof__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/esm/typeof */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");
/* harmony import */ var _babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/esm/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _config_provider__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../config-provider */ "./node_modules/antd/es/config-provider/context.js");
/* harmony import */ var _RowContext__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./RowContext */ "./node_modules/antd/es/grid/RowContext.js");
/* harmony import */ var _util_type__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../_util/type */ "./node_modules/antd/es/_util/type.js");
/* harmony import */ var _util_responsiveObserve__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../_util/responsiveObserve */ "./node_modules/antd/es/_util/responsiveObserve.js");
/* harmony import */ var _util_hooks_useFlexGapSupport__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../_util/hooks/useFlexGapSupport */ "./node_modules/antd/es/_util/hooks/useFlexGapSupport.js");





var __rest = undefined && undefined.__rest || function (s, e) {
  var t = {};

  for (var p in s) {
    if (Object.prototype.hasOwnProperty.call(s, p) && e.indexOf(p) < 0) t[p] = s[p];
  }

  if (s != null && typeof Object.getOwnPropertySymbols === "function") for (var i = 0, p = Object.getOwnPropertySymbols(s); i < p.length; i++) {
    if (e.indexOf(p[i]) < 0 && Object.prototype.propertyIsEnumerable.call(s, p[i])) t[p[i]] = s[p[i]];
  }
  return t;
};








var RowAligns = (0,_util_type__WEBPACK_IMPORTED_MODULE_6__.tuple)('top', 'middle', 'bottom', 'stretch');
var RowJustify = (0,_util_type__WEBPACK_IMPORTED_MODULE_6__.tuple)('start', 'end', 'center', 'space-around', 'space-between');
var Row = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_4__.forwardRef(function (props, ref) {
  var _classNames;

  var customizePrefixCls = props.prefixCls,
      justify = props.justify,
      align = props.align,
      className = props.className,
      style = props.style,
      children = props.children,
      _props$gutter = props.gutter,
      gutter = _props$gutter === void 0 ? 0 : _props$gutter,
      wrap = props.wrap,
      others = __rest(props, ["prefixCls", "justify", "align", "className", "style", "children", "gutter", "wrap"]);

  var _React$useContext = react__WEBPACK_IMPORTED_MODULE_4__.useContext(_config_provider__WEBPACK_IMPORTED_MODULE_7__.ConfigContext),
      getPrefixCls = _React$useContext.getPrefixCls,
      direction = _React$useContext.direction;

  var _React$useState = react__WEBPACK_IMPORTED_MODULE_4__.useState({
    xs: true,
    sm: true,
    md: true,
    lg: true,
    xl: true,
    xxl: true
  }),
      _React$useState2 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_3__["default"])(_React$useState, 2),
      screens = _React$useState2[0],
      setScreens = _React$useState2[1];

  var supportFlexGap = (0,_util_hooks_useFlexGapSupport__WEBPACK_IMPORTED_MODULE_8__["default"])();
  var gutterRef = react__WEBPACK_IMPORTED_MODULE_4__.useRef(gutter); // ================================== Effect ==================================

  react__WEBPACK_IMPORTED_MODULE_4__.useEffect(function () {
    var token = _util_responsiveObserve__WEBPACK_IMPORTED_MODULE_9__["default"].subscribe(function (screen) {
      var currentGutter = gutterRef.current || 0;

      if (!Array.isArray(currentGutter) && (0,_babel_runtime_helpers_esm_typeof__WEBPACK_IMPORTED_MODULE_2__["default"])(currentGutter) === 'object' || Array.isArray(currentGutter) && ((0,_babel_runtime_helpers_esm_typeof__WEBPACK_IMPORTED_MODULE_2__["default"])(currentGutter[0]) === 'object' || (0,_babel_runtime_helpers_esm_typeof__WEBPACK_IMPORTED_MODULE_2__["default"])(currentGutter[1]) === 'object')) {
        setScreens(screen);
      }
    });
    return function () {
      return _util_responsiveObserve__WEBPACK_IMPORTED_MODULE_9__["default"].unsubscribe(token);
    };
  }, []); // ================================== Render ==================================

  var getGutter = function getGutter() {
    var results = [0, 0];
    var normalizedGutter = Array.isArray(gutter) ? gutter : [gutter, 0];
    normalizedGutter.forEach(function (g, index) {
      if ((0,_babel_runtime_helpers_esm_typeof__WEBPACK_IMPORTED_MODULE_2__["default"])(g) === 'object') {
        for (var i = 0; i < _util_responsiveObserve__WEBPACK_IMPORTED_MODULE_9__.responsiveArray.length; i++) {
          var breakpoint = _util_responsiveObserve__WEBPACK_IMPORTED_MODULE_9__.responsiveArray[i];

          if (screens[breakpoint] && g[breakpoint] !== undefined) {
            results[index] = g[breakpoint];
            break;
          }
        }
      } else {
        results[index] = g || 0;
      }
    });
    return results;
  };

  var prefixCls = getPrefixCls('row', customizePrefixCls);
  var gutters = getGutter();
  var classes = classnames__WEBPACK_IMPORTED_MODULE_5___default()(prefixCls, (_classNames = {}, (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(_classNames, "".concat(prefixCls, "-no-wrap"), wrap === false), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(_classNames, "".concat(prefixCls, "-").concat(justify), justify), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(_classNames, "".concat(prefixCls, "-").concat(align), align), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(_classNames, "".concat(prefixCls, "-rtl"), direction === 'rtl'), _classNames), className); // Add gutter related style

  var rowStyle = {};
  var horizontalGutter = gutters[0] > 0 ? gutters[0] / -2 : undefined;
  var verticalGutter = gutters[1] > 0 ? gutters[1] / -2 : undefined;

  if (horizontalGutter) {
    rowStyle.marginLeft = horizontalGutter;
    rowStyle.marginRight = horizontalGutter;
  }

  if (supportFlexGap) {
    // Set gap direct if flex gap support
    var _gutters = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_3__["default"])(gutters, 2);

    rowStyle.rowGap = _gutters[1];
  } else if (verticalGutter) {
    rowStyle.marginTop = verticalGutter;
    rowStyle.marginBottom = verticalGutter;
  }

  var rowContext = react__WEBPACK_IMPORTED_MODULE_4__.useMemo(function () {
    return {
      gutter: gutters,
      wrap: wrap,
      supportFlexGap: supportFlexGap
    };
  }, [gutters, wrap, supportFlexGap]);
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_4__.createElement(_RowContext__WEBPACK_IMPORTED_MODULE_10__["default"].Provider, {
    value: rowContext
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_4__.createElement("div", (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({}, others, {
    className: classes,
    style: (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__["default"])((0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({}, rowStyle), style),
    ref: ref
  }), children));
});
Row.displayName = 'Row';
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Row);

/***/ }),

/***/ "./node_modules/antd/es/row/index.js":
/*!*******************************************!*\
  !*** ./node_modules/antd/es/row/index.js ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _grid__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../grid */ "./node_modules/antd/es/grid/row.js");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_grid__WEBPACK_IMPORTED_MODULE_0__["default"]);

/***/ }),

/***/ "./node_modules/antd/es/tabs/index.js":
/*!********************************************!*\
  !*** ./node_modules/antd/es/tabs/index.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/extends */ "./node_modules/@babel/runtime/helpers/esm/extends.js");
/* harmony import */ var _babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/esm/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var rc_tabs__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! rc-tabs */ "./node_modules/rc-tabs/es/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _ant_design_icons_es_icons_EllipsisOutlined__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @ant-design/icons/es/icons/EllipsisOutlined */ "./node_modules/@ant-design/icons/es/icons/EllipsisOutlined.js");
/* harmony import */ var _ant_design_icons_es_icons_PlusOutlined__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! @ant-design/icons/es/icons/PlusOutlined */ "./node_modules/@ant-design/icons/es/icons/PlusOutlined.js");
/* harmony import */ var _ant_design_icons_es_icons_CloseOutlined__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @ant-design/icons/es/icons/CloseOutlined */ "./node_modules/@ant-design/icons/es/icons/CloseOutlined.js");
/* harmony import */ var _util_devWarning__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../_util/devWarning */ "./node_modules/antd/es/_util/devWarning.js");
/* harmony import */ var _config_provider__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../config-provider */ "./node_modules/antd/es/config-provider/context.js");
/* harmony import */ var _config_provider_SizeContext__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../config-provider/SizeContext */ "./node_modules/antd/es/config-provider/SizeContext.js");



var __rest = undefined && undefined.__rest || function (s, e) {
  var t = {};

  for (var p in s) {
    if (Object.prototype.hasOwnProperty.call(s, p) && e.indexOf(p) < 0) t[p] = s[p];
  }

  if (s != null && typeof Object.getOwnPropertySymbols === "function") for (var i = 0, p = Object.getOwnPropertySymbols(s); i < p.length; i++) {
    if (e.indexOf(p[i]) < 0 && Object.prototype.propertyIsEnumerable.call(s, p[i])) t[p[i]] = s[p[i]];
  }
  return t;
};











function Tabs(_a) {
  var type = _a.type,
      className = _a.className,
      propSize = _a.size,
      _onEdit = _a.onEdit,
      hideAdd = _a.hideAdd,
      centered = _a.centered,
      addIcon = _a.addIcon,
      props = __rest(_a, ["type", "className", "size", "onEdit", "hideAdd", "centered", "addIcon"]);

  var customizePrefixCls = props.prefixCls,
      _props$moreIcon = props.moreIcon,
      moreIcon = _props$moreIcon === void 0 ? /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_ant_design_icons_es_icons_EllipsisOutlined__WEBPACK_IMPORTED_MODULE_5__["default"], null) : _props$moreIcon;

  var _React$useContext = react__WEBPACK_IMPORTED_MODULE_2__.useContext(_config_provider__WEBPACK_IMPORTED_MODULE_6__.ConfigContext),
      getPrefixCls = _React$useContext.getPrefixCls,
      direction = _React$useContext.direction;

  var prefixCls = getPrefixCls('tabs', customizePrefixCls);
  var editable;

  if (type === 'editable-card') {
    editable = {
      onEdit: function onEdit(editType, _ref) {
        var key = _ref.key,
            event = _ref.event;
        _onEdit === null || _onEdit === void 0 ? void 0 : _onEdit(editType === 'add' ? event : key, editType);
      },
      removeIcon: /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_ant_design_icons_es_icons_CloseOutlined__WEBPACK_IMPORTED_MODULE_7__["default"], null),
      addIcon: addIcon || /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_ant_design_icons_es_icons_PlusOutlined__WEBPACK_IMPORTED_MODULE_8__["default"], null),
      showAdd: hideAdd !== true
    };
  }

  var rootPrefixCls = getPrefixCls();
  (0,_util_devWarning__WEBPACK_IMPORTED_MODULE_9__["default"])(!('onPrevClick' in props) && !('onNextClick' in props), 'Tabs', '`onPrevClick` and `onNextClick` has been removed. Please use `onTabScroll` instead.');
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_config_provider_SizeContext__WEBPACK_IMPORTED_MODULE_10__["default"].Consumer, null, function (contextSize) {
    var _classNames;

    var size = propSize !== undefined ? propSize : contextSize;
    return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(rc_tabs__WEBPACK_IMPORTED_MODULE_3__["default"], (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({
      direction: direction,
      moreTransitionName: "".concat(rootPrefixCls, "-slide-up")
    }, props, {
      className: classnames__WEBPACK_IMPORTED_MODULE_4___default()((_classNames = {}, (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(_classNames, "".concat(prefixCls, "-").concat(size), size), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(_classNames, "".concat(prefixCls, "-card"), ['card', 'editable-card'].includes(type)), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(_classNames, "".concat(prefixCls, "-editable-card"), type === 'editable-card'), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(_classNames, "".concat(prefixCls, "-centered"), centered), _classNames), className),
      editable: editable,
      moreIcon: moreIcon,
      prefixCls: prefixCls
    }));
  });
}

Tabs.TabPane = rc_tabs__WEBPACK_IMPORTED_MODULE_3__.TabPane;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Tabs);

/***/ }),

/***/ "./Modules/Selection/Resources/assets/js/Components/DashboardCard.js":
/*!***************************************************************************!*\
  !*** ./Modules/Selection/Resources/assets/js/Components/DashboardCard.js ***!
  \***************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "DashboardCard": () => (/* binding */ DashboardCard)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var antd__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! antd */ "./node_modules/antd/es/card/index.js");
/* harmony import */ var antd__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! antd */ "./node_modules/antd/es/typography/index.js");
/* harmony import */ var antd_es_card_Meta__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! antd/es/card/Meta */ "./node_modules/antd/es/card/Meta.js");
/* harmony import */ var _resources_js_src_Shared_Box_BoxFlexCenter__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../../../resources/js/src/Shared/Box/BoxFlexCenter */ "./resources/js/src/Shared/Box/BoxFlexCenter.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react/jsx-runtime */ "./node_modules/react/jsx-runtime.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) { symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); } keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }






var DashboardCard = function DashboardCard(_ref) {
  var title = _ref.title,
      src = _ref.src,
      onClick = _ref.onClick,
      style = _ref.style;
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)(antd__WEBPACK_IMPORTED_MODULE_3__["default"], {
    hoverable: true,
    cover: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("img", {
      alt: "example",
      src: src
    }),
    onClick: onClick,
    style: _objectSpread(_objectSpread({}, style), {}, {
      borderRadius: 10
    }),
    children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)(antd_es_card_Meta__WEBPACK_IMPORTED_MODULE_4__["default"], {
      title: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)(_resources_js_src_Shared_Box_BoxFlexCenter__WEBPACK_IMPORTED_MODULE_1__.BoxFlexCenter, {
        children: /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)(antd__WEBPACK_IMPORTED_MODULE_5__["default"], {
          color: "primary",
          children: title
        })
      })
    })
  });
};

/***/ }),

/***/ "./resources/js/src/Pages/Dashboard.js":
/*!*********************************************!*\
  !*** ./resources/js/src/Pages/Dashboard.js ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var antd__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! antd */ "./node_modules/antd/es/message/index.js");
/* harmony import */ var antd__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! antd */ "./node_modules/antd/es/col/index.js");
/* harmony import */ var _Modules_Selection_Resources_assets_js_Components_DashboardCard__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../Modules/Selection/Resources/assets/js/Components/DashboardCard */ "./Modules/Selection/Resources/assets/js/Components/DashboardCard.js");
/* harmony import */ var _inertiajs_inertia__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @inertiajs/inertia */ "./node_modules/@inertiajs/inertia/dist/index.js");
/* harmony import */ var _translation_lang__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../translation/lang */ "./resources/js/translation/lang.js");
/* harmony import */ var _Shared_JustifiedRow__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../Shared/JustifiedRow */ "./resources/js/src/Shared/JustifiedRow.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! react/jsx-runtime */ "./node_modules/react/jsx-runtime.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) { symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); } keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }










var Dashboard = function Dashboard() {
  var cards = [{
    title: _translation_lang__WEBPACK_IMPORTED_MODULE_3__["default"].get('pages.dashboard.select'),
    src: '/img/dashboard/pumpselection.jpg',
    onClick: function onClick() {
      _inertiajs_inertia__WEBPACK_IMPORTED_MODULE_2__.Inertia.get(route('projects.index'));
    }
  }, {
    title: _translation_lang__WEBPACK_IMPORTED_MODULE_3__["default"].get('pages.dashboard.marketplace'),
    src: '/img/dashboard/marketplace.png',
    onClick: function onClick() {
      antd__WEBPACK_IMPORTED_MODULE_6__["default"].info(_translation_lang__WEBPACK_IMPORTED_MODULE_3__["default"].get('messages.service_development'));
    }
  }, {
    title: _translation_lang__WEBPACK_IMPORTED_MODULE_3__["default"].get('pages.dashboard.pumps'),
    src: '/img/dashboard/pumps.jpg',
    onClick: function onClick() {
      _inertiajs_inertia__WEBPACK_IMPORTED_MODULE_2__.Inertia.get(route('pumps.index'));
    }
  }];
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(_Shared_JustifiedRow__WEBPACK_IMPORTED_MODULE_4__.JustifiedRow, {
    children: cards.map(function (card) {
      return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(antd__WEBPACK_IMPORTED_MODULE_7__["default"], {
        md: 7,
        xs: 11,
        children: /*#__PURE__*/(0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Modules_Selection_Resources_assets_js_Components_DashboardCard__WEBPACK_IMPORTED_MODULE_1__.DashboardCard, _objectSpread(_objectSpread({}, card), {}, {
          key: card.title
        }))
      }, card.title);
    })
  });
};

Dashboard.layout = function (page) {
  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_5__.jsx)(Common, {
    children: page,
    title: _translation_lang__WEBPACK_IMPORTED_MODULE_3__["default"].get('pages.dashboard.title')
  });
};

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Dashboard);

/***/ }),

/***/ "./resources/js/src/Shared/Box/BoxFlex.js":
/*!************************************************!*\
  !*** ./resources/js/src/Shared/Box/BoxFlex.js ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "BoxFlex": () => (/* binding */ BoxFlex)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var _Hooks_styles_hook__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../Hooks/styles.hook */ "./resources/js/src/Hooks/styles.hook.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react/jsx-runtime */ "./node_modules/react/jsx-runtime.js");
var _excluded = ["children", "style"];

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) { symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); } keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _objectWithoutProperties(source, excluded) { if (source == null) return {}; var target = _objectWithoutPropertiesLoose(source, excluded); var key, i; if (Object.getOwnPropertySymbols) { var sourceSymbolKeys = Object.getOwnPropertySymbols(source); for (i = 0; i < sourceSymbolKeys.length; i++) { key = sourceSymbolKeys[i]; if (excluded.indexOf(key) >= 0) continue; if (!Object.prototype.propertyIsEnumerable.call(source, key)) continue; target[key] = source[key]; } } return target; }

function _objectWithoutPropertiesLoose(source, excluded) { if (source == null) return {}; var target = {}; var sourceKeys = Object.keys(source); var key, i; for (i = 0; i < sourceKeys.length; i++) { key = sourceKeys[i]; if (excluded.indexOf(key) >= 0) continue; target[key] = source[key]; } return target; }




var BoxFlex = function BoxFlex(_ref) {
  var children = _ref.children,
      style = _ref.style,
      rest = _objectWithoutProperties(_ref, _excluded);

  var _useStyles = (0,_Hooks_styles_hook__WEBPACK_IMPORTED_MODULE_1__.useStyles)(),
      displayFlex = _useStyles.displayFlex;

  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)("div", _objectSpread(_objectSpread({
    style: _objectSpread(_objectSpread({}, displayFlex), style)
  }, rest), {}, {
    children: children
  }));
};

/***/ }),

/***/ "./resources/js/src/Shared/Box/BoxFlexCenter.js":
/*!******************************************************!*\
  !*** ./resources/js/src/Shared/Box/BoxFlexCenter.js ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "BoxFlexCenter": () => (/* binding */ BoxFlexCenter)
/* harmony export */ });
/* harmony import */ var _BoxFlex__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./BoxFlex */ "./resources/js/src/Shared/Box/BoxFlex.js");
/* harmony import */ var _Hooks_styles_hook__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../Hooks/styles.hook */ "./resources/js/src/Hooks/styles.hook.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react/jsx-runtime */ "./node_modules/react/jsx-runtime.js");
var _excluded = ["children", "style"];

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) { symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); } keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _objectWithoutProperties(source, excluded) { if (source == null) return {}; var target = _objectWithoutPropertiesLoose(source, excluded); var key, i; if (Object.getOwnPropertySymbols) { var sourceSymbolKeys = Object.getOwnPropertySymbols(source); for (i = 0; i < sourceSymbolKeys.length; i++) { key = sourceSymbolKeys[i]; if (excluded.indexOf(key) >= 0) continue; if (!Object.prototype.propertyIsEnumerable.call(source, key)) continue; target[key] = source[key]; } } return target; }

function _objectWithoutPropertiesLoose(source, excluded) { if (source == null) return {}; var target = {}; var sourceKeys = Object.keys(source); var key, i; for (i = 0; i < sourceKeys.length; i++) { key = sourceKeys[i]; if (excluded.indexOf(key) >= 0) continue; target[key] = source[key]; } return target; }




var BoxFlexCenter = function BoxFlexCenter(_ref) {
  var children = _ref.children,
      style = _ref.style,
      rest = _objectWithoutProperties(_ref, _excluded);

  var _useStyles = (0,_Hooks_styles_hook__WEBPACK_IMPORTED_MODULE_1__.useStyles)(),
      justifyContentCenter = _useStyles.justifyContentCenter;

  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_2__.jsx)(_BoxFlex__WEBPACK_IMPORTED_MODULE_0__.BoxFlex, _objectSpread(_objectSpread({
    style: _objectSpread(_objectSpread({}, justifyContentCenter), style)
  }, rest), {}, {
    children: children
  }));
};

/***/ }),

/***/ "./resources/js/src/Shared/JustifiedRow.js":
/*!*************************************************!*\
  !*** ./resources/js/src/Shared/JustifiedRow.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "JustifiedRow": () => (/* binding */ JustifiedRow)
/* harmony export */ });
/* harmony import */ var antd__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! antd */ "./node_modules/antd/es/row/index.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react/jsx-runtime */ "./node_modules/react/jsx-runtime.js");
var _excluded = ["children", "gutter"];

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) { symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); } keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _objectWithoutProperties(source, excluded) { if (source == null) return {}; var target = _objectWithoutPropertiesLoose(source, excluded); var key, i; if (Object.getOwnPropertySymbols) { var sourceSymbolKeys = Object.getOwnPropertySymbols(source); for (i = 0; i < sourceSymbolKeys.length; i++) { key = sourceSymbolKeys[i]; if (excluded.indexOf(key) >= 0) continue; if (!Object.prototype.propertyIsEnumerable.call(source, key)) continue; target[key] = source[key]; } } return target; }

function _objectWithoutPropertiesLoose(source, excluded) { if (source == null) return {}; var target = {}; var sourceKeys = Object.keys(source); var key, i; for (i = 0; i < sourceKeys.length; i++) { key = sourceKeys[i]; if (excluded.indexOf(key) >= 0) continue; target[key] = source[key]; } return target; }




var JustifiedRow = function JustifiedRow(_ref) {
  var children = _ref.children,
      _ref$gutter = _ref.gutter,
      gutter = _ref$gutter === void 0 ? [0, 0] : _ref$gutter,
      rest = _objectWithoutProperties(_ref, _excluded);

  return /*#__PURE__*/(0,react_jsx_runtime__WEBPACK_IMPORTED_MODULE_1__.jsx)(antd__WEBPACK_IMPORTED_MODULE_2__["default"], _objectSpread(_objectSpread({
    style: {
      minHeight: "85vh"
    },
    justify: "space-around",
    align: "middle",
    gutter: gutter
  }, rest), {}, {
    children: children
  }));
};

/***/ }),

/***/ "./node_modules/rc-tabs/es/TabContext.js":
/*!***********************************************!*\
  !*** ./node_modules/rc-tabs/es/TabContext.js ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (/*#__PURE__*/(0,react__WEBPACK_IMPORTED_MODULE_0__.createContext)(null));

/***/ }),

/***/ "./node_modules/rc-tabs/es/TabNavList/AddButton.js":
/*!*********************************************************!*\
  !*** ./node_modules/rc-tabs/es/TabNavList/AddButton.js ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");


function AddButton(_ref, ref) {
  var prefixCls = _ref.prefixCls,
      editable = _ref.editable,
      locale = _ref.locale,
      style = _ref.style;

  if (!editable || editable.showAdd === false) {
    return null;
  }

  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0__.createElement("button", {
    ref: ref,
    type: "button",
    className: "".concat(prefixCls, "-nav-add"),
    style: style,
    "aria-label": (locale === null || locale === void 0 ? void 0 : locale.addAriaLabel) || 'Add tab',
    onClick: function onClick(event) {
      editable.onEdit('add', {
        event: event
      });
    }
  }, editable.addIcon || '+');
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (/*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0__.forwardRef(AddButton));

/***/ }),

/***/ "./node_modules/rc-tabs/es/TabNavList/OperationNode.js":
/*!*************************************************************!*\
  !*** ./node_modules/rc-tabs/es/TabNavList/OperationNode.js ***!
  \*************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/esm/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var rc_util_es_KeyCode__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! rc-util/es/KeyCode */ "./node_modules/rc-util/es/KeyCode.js");
/* harmony import */ var rc_menu__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! rc-menu */ "./node_modules/rc-menu/es/index.js");
/* harmony import */ var rc_dropdown__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! rc-dropdown */ "./node_modules/rc-dropdown/es/index.js");
/* harmony import */ var _AddButton__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./AddButton */ "./node_modules/rc-tabs/es/TabNavList/AddButton.js");










function OperationNode(_ref, ref) {
  var prefixCls = _ref.prefixCls,
      id = _ref.id,
      tabs = _ref.tabs,
      locale = _ref.locale,
      mobile = _ref.mobile,
      _ref$moreIcon = _ref.moreIcon,
      moreIcon = _ref$moreIcon === void 0 ? 'More' : _ref$moreIcon,
      moreTransitionName = _ref.moreTransitionName,
      style = _ref.style,
      className = _ref.className,
      editable = _ref.editable,
      tabBarGutter = _ref.tabBarGutter,
      rtl = _ref.rtl,
      removeAriaLabel = _ref.removeAriaLabel,
      onTabClick = _ref.onTabClick;

  // ======================== Dropdown ========================
  var _useState = (0,react__WEBPACK_IMPORTED_MODULE_2__.useState)(false),
      _useState2 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_1__["default"])(_useState, 2),
      open = _useState2[0],
      setOpen = _useState2[1];

  var _useState3 = (0,react__WEBPACK_IMPORTED_MODULE_2__.useState)(null),
      _useState4 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_1__["default"])(_useState3, 2),
      selectedKey = _useState4[0],
      setSelectedKey = _useState4[1];

  var popupId = "".concat(id, "-more-popup");
  var dropdownPrefix = "".concat(prefixCls, "-dropdown");
  var selectedItemId = selectedKey !== null ? "".concat(popupId, "-").concat(selectedKey) : null;
  var dropdownAriaLabel = locale === null || locale === void 0 ? void 0 : locale.dropdownAriaLabel;

  function onRemoveTab(event, key) {
    event.preventDefault();
    event.stopPropagation();
    editable.onEdit('remove', {
      key: key,
      event: event
    });
  }

  var menu = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(rc_menu__WEBPACK_IMPORTED_MODULE_5__["default"], {
    onClick: function onClick(_ref2) {
      var key = _ref2.key,
          domEvent = _ref2.domEvent;
      onTabClick(key, domEvent);
      setOpen(false);
    },
    id: popupId,
    tabIndex: -1,
    role: "listbox",
    "aria-activedescendant": selectedItemId,
    selectedKeys: [selectedKey],
    "aria-label": dropdownAriaLabel !== undefined ? dropdownAriaLabel : 'expanded dropdown'
  }, tabs.map(function (tab) {
    var removable = editable && tab.closable !== false && !tab.disabled;
    return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(rc_menu__WEBPACK_IMPORTED_MODULE_5__.MenuItem, {
      key: tab.key,
      id: "".concat(popupId, "-").concat(tab.key),
      role: "option",
      "aria-controls": id && "".concat(id, "-panel-").concat(tab.key),
      disabled: tab.disabled
    }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement("span", null, tab.tab), removable && /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement("button", {
      type: "button",
      "aria-label": removeAriaLabel || 'remove',
      tabIndex: 0,
      className: "".concat(dropdownPrefix, "-menu-item-remove"),
      onClick: function onClick(e) {
        e.stopPropagation();
        onRemoveTab(e, tab.key);
      }
    }, tab.closeIcon || editable.removeIcon || '×'));
  }));

  function selectOffset(offset) {
    var enabledTabs = tabs.filter(function (tab) {
      return !tab.disabled;
    });
    var selectedIndex = enabledTabs.findIndex(function (tab) {
      return tab.key === selectedKey;
    }) || 0;
    var len = enabledTabs.length;

    for (var i = 0; i < len; i += 1) {
      selectedIndex = (selectedIndex + offset + len) % len;
      var tab = enabledTabs[selectedIndex];

      if (!tab.disabled) {
        setSelectedKey(tab.key);
        return;
      }
    }
  }

  function onKeyDown(e) {
    var which = e.which;

    if (!open) {
      if ([rc_util_es_KeyCode__WEBPACK_IMPORTED_MODULE_4__["default"].DOWN, rc_util_es_KeyCode__WEBPACK_IMPORTED_MODULE_4__["default"].SPACE, rc_util_es_KeyCode__WEBPACK_IMPORTED_MODULE_4__["default"].ENTER].includes(which)) {
        setOpen(true);
        e.preventDefault();
      }

      return;
    }

    switch (which) {
      case rc_util_es_KeyCode__WEBPACK_IMPORTED_MODULE_4__["default"].UP:
        selectOffset(-1);
        e.preventDefault();
        break;

      case rc_util_es_KeyCode__WEBPACK_IMPORTED_MODULE_4__["default"].DOWN:
        selectOffset(1);
        e.preventDefault();
        break;

      case rc_util_es_KeyCode__WEBPACK_IMPORTED_MODULE_4__["default"].ESC:
        setOpen(false);
        break;

      case rc_util_es_KeyCode__WEBPACK_IMPORTED_MODULE_4__["default"].SPACE:
      case rc_util_es_KeyCode__WEBPACK_IMPORTED_MODULE_4__["default"].ENTER:
        if (selectedKey !== null) onTabClick(selectedKey, e);
        break;
    }
  } // ========================= Effect =========================


  (0,react__WEBPACK_IMPORTED_MODULE_2__.useEffect)(function () {
    // We use query element here to avoid React strict warning
    var ele = document.getElementById(selectedItemId);

    if (ele && ele.scrollIntoView) {
      ele.scrollIntoView(false);
    }
  }, [selectedKey]);
  (0,react__WEBPACK_IMPORTED_MODULE_2__.useEffect)(function () {
    if (!open) {
      setSelectedKey(null);
    }
  }, [open]); // ========================= Render =========================

  var moreStyle = (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])({}, rtl ? 'marginRight' : 'marginLeft', tabBarGutter);

  if (!tabs.length) {
    moreStyle.visibility = 'hidden';
    moreStyle.order = 1;
  }

  var overlayClassName = classnames__WEBPACK_IMPORTED_MODULE_3___default()((0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])({}, "".concat(dropdownPrefix, "-rtl"), rtl));
  var moreNode = mobile ? null : /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(rc_dropdown__WEBPACK_IMPORTED_MODULE_6__["default"], {
    prefixCls: dropdownPrefix,
    overlay: menu,
    trigger: ['hover'],
    visible: open,
    transitionName: moreTransitionName,
    onVisibleChange: setOpen,
    overlayClassName: overlayClassName,
    mouseEnterDelay: 0.1,
    mouseLeaveDelay: 0.1
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement("button", {
    type: "button",
    className: "".concat(prefixCls, "-nav-more"),
    style: moreStyle,
    tabIndex: -1,
    "aria-hidden": "true",
    "aria-haspopup": "listbox",
    "aria-controls": popupId,
    id: "".concat(id, "-more"),
    "aria-expanded": open,
    onKeyDown: onKeyDown
  }, moreIcon));
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement("div", {
    className: classnames__WEBPACK_IMPORTED_MODULE_3___default()("".concat(prefixCls, "-nav-operations"), className),
    style: style,
    ref: ref
  }, moreNode, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement(_AddButton__WEBPACK_IMPORTED_MODULE_7__["default"], {
    prefixCls: prefixCls,
    locale: locale,
    editable: editable
  }));
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (/*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.memo( /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.forwardRef(OperationNode), function (_, next) {
  return (// https://github.com/ant-design/ant-design/issues/32544
    // We'd better remove syntactic sugar in `rc-menu` since this has perf issue
    next.tabMoving
  );
}));

/***/ }),

/***/ "./node_modules/rc-tabs/es/TabNavList/TabNode.js":
/*!*******************************************************!*\
  !*** ./node_modules/rc-tabs/es/TabNavList/TabNode.js ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var rc_util_es_KeyCode__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! rc-util/es/KeyCode */ "./node_modules/rc-util/es/KeyCode.js");





function TabNode(_ref, ref) {
  var _classNames;

  var prefixCls = _ref.prefixCls,
      id = _ref.id,
      active = _ref.active,
      _ref$tab = _ref.tab,
      key = _ref$tab.key,
      tab = _ref$tab.tab,
      disabled = _ref$tab.disabled,
      closeIcon = _ref$tab.closeIcon,
      closable = _ref.closable,
      renderWrapper = _ref.renderWrapper,
      removeAriaLabel = _ref.removeAriaLabel,
      editable = _ref.editable,
      onClick = _ref.onClick,
      onRemove = _ref.onRemove,
      onFocus = _ref.onFocus,
      style = _ref.style,
      className = _ref.className;
  var tabPrefix = "".concat(prefixCls, "-tab");
  react__WEBPACK_IMPORTED_MODULE_1__.useEffect(function () {
    return onRemove;
  }, []);
  var removable = editable && closable !== false && !disabled;

  function onInternalClick(e) {
    if (disabled) {
      return;
    }

    onClick(e);
  }

  function onRemoveTab(event) {
    event.preventDefault();
    event.stopPropagation();
    editable.onEdit('remove', {
      key: key,
      event: event
    });
  }

  var node = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_1__.createElement("div", {
    key: key,
    ref: ref,
    className: classnames__WEBPACK_IMPORTED_MODULE_2___default()(tabPrefix, className, (_classNames = {}, (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_classNames, "".concat(tabPrefix, "-with-remove"), removable), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_classNames, "".concat(tabPrefix, "-active"), active), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])(_classNames, "".concat(tabPrefix, "-disabled"), disabled), _classNames)),
    style: style,
    onClick: onInternalClick
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_1__.createElement("div", {
    role: "tab",
    "aria-selected": active,
    id: id && "".concat(id, "-tab-").concat(key),
    className: "".concat(tabPrefix, "-btn"),
    "aria-controls": id && "".concat(id, "-panel-").concat(key),
    "aria-disabled": disabled,
    tabIndex: disabled ? null : 0,
    onClick: function onClick(e) {
      e.stopPropagation();
      onInternalClick(e);
    },
    onKeyDown: function onKeyDown(e) {
      if ([rc_util_es_KeyCode__WEBPACK_IMPORTED_MODULE_3__["default"].SPACE, rc_util_es_KeyCode__WEBPACK_IMPORTED_MODULE_3__["default"].ENTER].includes(e.which)) {
        e.preventDefault();
        onInternalClick(e);
      }
    },
    onFocus: onFocus
  }, tab), removable && /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_1__.createElement("button", {
    type: "button",
    "aria-label": removeAriaLabel || 'remove',
    tabIndex: 0,
    className: "".concat(tabPrefix, "-remove"),
    onClick: function onClick(e) {
      e.stopPropagation();
      onRemoveTab(e);
    }
  }, closeIcon || editable.removeIcon || '×'));
  return renderWrapper ? renderWrapper(node) : node;
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (/*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_1__.forwardRef(TabNode));

/***/ }),

/***/ "./node_modules/rc-tabs/es/TabNavList/index.js":
/*!*****************************************************!*\
  !*** ./node_modules/rc-tabs/es/TabNavList/index.js ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/extends */ "./node_modules/@babel/runtime/helpers/esm/extends.js");
/* harmony import */ var _babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/esm/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_esm_toConsumableArray__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/esm/toConsumableArray */ "./node_modules/@babel/runtime/helpers/esm/toConsumableArray.js");
/* harmony import */ var _babel_runtime_helpers_esm_objectSpread2__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/esm/objectSpread2 */ "./node_modules/@babel/runtime/helpers/esm/objectSpread2.js");
/* harmony import */ var _babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/esm/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_esm_typeof__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/esm/typeof */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var rc_util_es_raf__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! rc-util/es/raf */ "./node_modules/rc-util/es/raf.js");
/* harmony import */ var rc_resize_observer__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! rc-resize-observer */ "./node_modules/rc-resize-observer/es/index.js");
/* harmony import */ var _hooks_useRaf__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ../hooks/useRaf */ "./node_modules/rc-tabs/es/hooks/useRaf.js");
/* harmony import */ var _TabNode__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./TabNode */ "./node_modules/rc-tabs/es/TabNavList/TabNode.js");
/* harmony import */ var _hooks_useOffsets__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ../hooks/useOffsets */ "./node_modules/rc-tabs/es/hooks/useOffsets.js");
/* harmony import */ var _hooks_useVisibleRange__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ../hooks/useVisibleRange */ "./node_modules/rc-tabs/es/hooks/useVisibleRange.js");
/* harmony import */ var _OperationNode__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./OperationNode */ "./node_modules/rc-tabs/es/TabNavList/OperationNode.js");
/* harmony import */ var _TabContext__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ../TabContext */ "./node_modules/rc-tabs/es/TabContext.js");
/* harmony import */ var _hooks_useTouchMove__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ../hooks/useTouchMove */ "./node_modules/rc-tabs/es/hooks/useTouchMove.js");
/* harmony import */ var _hooks_useRefs__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ../hooks/useRefs */ "./node_modules/rc-tabs/es/hooks/useRefs.js");
/* harmony import */ var _AddButton__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! ./AddButton */ "./node_modules/rc-tabs/es/TabNavList/AddButton.js");
/* harmony import */ var _hooks_useSyncState__WEBPACK_IMPORTED_MODULE_19__ = __webpack_require__(/*! ../hooks/useSyncState */ "./node_modules/rc-tabs/es/hooks/useSyncState.js");






















var ExtraContent = function ExtraContent(_ref) {
  var position = _ref.position,
      prefixCls = _ref.prefixCls,
      extra = _ref.extra;
  if (!extra) return null;
  var content; // Parse extra

  var assertExtra = {};

  if (extra && (0,_babel_runtime_helpers_esm_typeof__WEBPACK_IMPORTED_MODULE_5__["default"])(extra) === 'object' && ! /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_6__.isValidElement(extra)) {
    assertExtra = extra;
  } else {
    assertExtra.right = extra;
  }

  if (position === 'right') {
    content = assertExtra.right;
  }

  if (position === 'left') {
    content = assertExtra.left;
  }

  return content ? /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_6__.createElement("div", {
    className: "".concat(prefixCls, "-extra-content")
  }, content) : null;
};

function TabNavList(props, ref) {
  var _classNames;

  var _React$useContext = react__WEBPACK_IMPORTED_MODULE_6__.useContext(_TabContext__WEBPACK_IMPORTED_MODULE_15__["default"]),
      prefixCls = _React$useContext.prefixCls,
      tabs = _React$useContext.tabs;

  var className = props.className,
      style = props.style,
      id = props.id,
      animated = props.animated,
      activeKey = props.activeKey,
      rtl = props.rtl,
      extra = props.extra,
      editable = props.editable,
      locale = props.locale,
      tabPosition = props.tabPosition,
      tabBarGutter = props.tabBarGutter,
      children = props.children,
      onTabClick = props.onTabClick,
      onTabScroll = props.onTabScroll;
  var tabsWrapperRef = (0,react__WEBPACK_IMPORTED_MODULE_6__.useRef)();
  var tabListRef = (0,react__WEBPACK_IMPORTED_MODULE_6__.useRef)();
  var operationsRef = (0,react__WEBPACK_IMPORTED_MODULE_6__.useRef)();
  var innerAddButtonRef = (0,react__WEBPACK_IMPORTED_MODULE_6__.useRef)();

  var _useRefs = (0,_hooks_useRefs__WEBPACK_IMPORTED_MODULE_17__["default"])(),
      _useRefs2 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_4__["default"])(_useRefs, 2),
      getBtnRef = _useRefs2[0],
      removeBtnRef = _useRefs2[1];

  var tabPositionTopOrBottom = tabPosition === 'top' || tabPosition === 'bottom';

  var _useSyncState = (0,_hooks_useSyncState__WEBPACK_IMPORTED_MODULE_19__["default"])(0, function (next, prev) {
    if (tabPositionTopOrBottom && onTabScroll) {
      onTabScroll({
        direction: next > prev ? 'left' : 'right'
      });
    }
  }),
      _useSyncState2 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_4__["default"])(_useSyncState, 2),
      transformLeft = _useSyncState2[0],
      setTransformLeft = _useSyncState2[1];

  var _useSyncState3 = (0,_hooks_useSyncState__WEBPACK_IMPORTED_MODULE_19__["default"])(0, function (next, prev) {
    if (!tabPositionTopOrBottom && onTabScroll) {
      onTabScroll({
        direction: next > prev ? 'top' : 'bottom'
      });
    }
  }),
      _useSyncState4 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_4__["default"])(_useSyncState3, 2),
      transformTop = _useSyncState4[0],
      setTransformTop = _useSyncState4[1];

  var _useState = (0,react__WEBPACK_IMPORTED_MODULE_6__.useState)(0),
      _useState2 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_4__["default"])(_useState, 2),
      wrapperScrollWidth = _useState2[0],
      setWrapperScrollWidth = _useState2[1];

  var _useState3 = (0,react__WEBPACK_IMPORTED_MODULE_6__.useState)(0),
      _useState4 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_4__["default"])(_useState3, 2),
      wrapperScrollHeight = _useState4[0],
      setWrapperScrollHeight = _useState4[1];

  var _useState5 = (0,react__WEBPACK_IMPORTED_MODULE_6__.useState)(0),
      _useState6 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_4__["default"])(_useState5, 2),
      wrapperContentWidth = _useState6[0],
      setWrapperContentWidth = _useState6[1];

  var _useState7 = (0,react__WEBPACK_IMPORTED_MODULE_6__.useState)(0),
      _useState8 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_4__["default"])(_useState7, 2),
      wrapperContentHeight = _useState8[0],
      setWrapperContentHeight = _useState8[1];

  var _useState9 = (0,react__WEBPACK_IMPORTED_MODULE_6__.useState)(null),
      _useState10 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_4__["default"])(_useState9, 2),
      wrapperWidth = _useState10[0],
      setWrapperWidth = _useState10[1];

  var _useState11 = (0,react__WEBPACK_IMPORTED_MODULE_6__.useState)(null),
      _useState12 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_4__["default"])(_useState11, 2),
      wrapperHeight = _useState12[0],
      setWrapperHeight = _useState12[1];

  var _useState13 = (0,react__WEBPACK_IMPORTED_MODULE_6__.useState)(0),
      _useState14 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_4__["default"])(_useState13, 2),
      addWidth = _useState14[0],
      setAddWidth = _useState14[1];

  var _useState15 = (0,react__WEBPACK_IMPORTED_MODULE_6__.useState)(0),
      _useState16 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_4__["default"])(_useState15, 2),
      addHeight = _useState16[0],
      setAddHeight = _useState16[1];

  var _useRafState = (0,_hooks_useRaf__WEBPACK_IMPORTED_MODULE_10__.useRafState)(new Map()),
      _useRafState2 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_4__["default"])(_useRafState, 2),
      tabSizes = _useRafState2[0],
      setTabSizes = _useRafState2[1];

  var tabOffsets = (0,_hooks_useOffsets__WEBPACK_IMPORTED_MODULE_12__["default"])(tabs, tabSizes, wrapperScrollWidth); // ========================== Util =========================

  var operationsHiddenClassName = "".concat(prefixCls, "-nav-operations-hidden");
  var transformMin = 0;
  var transformMax = 0;

  if (!tabPositionTopOrBottom) {
    transformMin = Math.min(0, wrapperHeight - wrapperScrollHeight);
    transformMax = 0;
  } else if (rtl) {
    transformMin = 0;
    transformMax = Math.max(0, wrapperScrollWidth - wrapperWidth);
  } else {
    transformMin = Math.min(0, wrapperWidth - wrapperScrollWidth);
    transformMax = 0;
  }

  function alignInRange(value) {
    if (value < transformMin) {
      return transformMin;
    }

    if (value > transformMax) {
      return transformMax;
    }

    return value;
  } // ========================= Mobile ========================


  var touchMovingRef = (0,react__WEBPACK_IMPORTED_MODULE_6__.useRef)();

  var _useState17 = (0,react__WEBPACK_IMPORTED_MODULE_6__.useState)(),
      _useState18 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_4__["default"])(_useState17, 2),
      lockAnimation = _useState18[0],
      setLockAnimation = _useState18[1];

  function doLockAnimation() {
    setLockAnimation(Date.now());
  }

  function clearTouchMoving() {
    window.clearTimeout(touchMovingRef.current);
  }

  (0,_hooks_useTouchMove__WEBPACK_IMPORTED_MODULE_16__["default"])(tabsWrapperRef, function (offsetX, offsetY) {
    function doMove(setState, offset) {
      setState(function (value) {
        var newValue = alignInRange(value + offset);
        return newValue;
      });
    }

    if (tabPositionTopOrBottom) {
      // Skip scroll if place is enough
      if (wrapperWidth >= wrapperScrollWidth) {
        return false;
      }

      doMove(setTransformLeft, offsetX);
    } else {
      if (wrapperHeight >= wrapperScrollHeight) {
        return false;
      }

      doMove(setTransformTop, offsetY);
    }

    clearTouchMoving();
    doLockAnimation();
    return true;
  });
  (0,react__WEBPACK_IMPORTED_MODULE_6__.useEffect)(function () {
    clearTouchMoving();

    if (lockAnimation) {
      touchMovingRef.current = window.setTimeout(function () {
        setLockAnimation(0);
      }, 100);
    }

    return clearTouchMoving;
  }, [lockAnimation]); // ========================= Scroll ========================

  function scrollToTab() {
    var key = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : activeKey;
    var tabOffset = tabOffsets.get(key) || {
      width: 0,
      height: 0,
      left: 0,
      right: 0,
      top: 0
    };

    if (tabPositionTopOrBottom) {
      // ============ Align with top & bottom ============
      var newTransform = transformLeft; // RTL

      if (rtl) {
        if (tabOffset.right < transformLeft) {
          newTransform = tabOffset.right;
        } else if (tabOffset.right + tabOffset.width > transformLeft + wrapperWidth) {
          newTransform = tabOffset.right + tabOffset.width - wrapperWidth;
        }
      } // LTR
      else if (tabOffset.left < -transformLeft) {
        newTransform = -tabOffset.left;
      } else if (tabOffset.left + tabOffset.width > -transformLeft + wrapperWidth) {
        newTransform = -(tabOffset.left + tabOffset.width - wrapperWidth);
      }

      setTransformTop(0);
      setTransformLeft(alignInRange(newTransform));
    } else {
      // ============ Align with left & right ============
      var _newTransform = transformTop;

      if (tabOffset.top < -transformTop) {
        _newTransform = -tabOffset.top;
      } else if (tabOffset.top + tabOffset.height > -transformTop + wrapperHeight) {
        _newTransform = -(tabOffset.top + tabOffset.height - wrapperHeight);
      }

      setTransformLeft(0);
      setTransformTop(alignInRange(_newTransform));
    }
  } // ========================== Tab ==========================
  // Render tab node & collect tab offset


  var _useVisibleRange = (0,_hooks_useVisibleRange__WEBPACK_IMPORTED_MODULE_13__["default"])(tabOffsets, {
    width: wrapperWidth,
    height: wrapperHeight,
    left: transformLeft,
    top: transformTop
  }, {
    width: wrapperContentWidth,
    height: wrapperContentHeight
  }, {
    width: addWidth,
    height: addHeight
  }, (0,_babel_runtime_helpers_esm_objectSpread2__WEBPACK_IMPORTED_MODULE_3__["default"])((0,_babel_runtime_helpers_esm_objectSpread2__WEBPACK_IMPORTED_MODULE_3__["default"])({}, props), {}, {
    tabs: tabs
  })),
      _useVisibleRange2 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_4__["default"])(_useVisibleRange, 2),
      visibleStart = _useVisibleRange2[0],
      visibleEnd = _useVisibleRange2[1];

  var tabNodeStyle = {};

  if (tabPosition === 'top' || tabPosition === 'bottom') {
    tabNodeStyle[rtl ? 'marginRight' : 'marginLeft'] = tabBarGutter;
  } else {
    tabNodeStyle.marginTop = tabBarGutter;
  }

  var tabNodes = tabs.map(function (tab, i) {
    var key = tab.key,
        tabClassName = tab.className;
    return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_6__.createElement(_TabNode__WEBPACK_IMPORTED_MODULE_11__["default"], {
      id: id,
      prefixCls: prefixCls,
      key: key,
      tab: tab
      /* first node should not have margin left */
      ,
      style: i === 0 ? undefined : tabNodeStyle,
      className: tabClassName,
      closable: tab.closable,
      editable: editable,
      active: key === activeKey,
      renderWrapper: children,
      removeAriaLabel: locale === null || locale === void 0 ? void 0 : locale.removeAriaLabel,
      ref: getBtnRef(key),
      onClick: function onClick(e) {
        onTabClick(key, e);
      },
      onRemove: function onRemove() {
        removeBtnRef(key);
      },
      onFocus: function onFocus() {
        scrollToTab(key);
        doLockAnimation();

        if (!tabsWrapperRef.current) {
          return;
        } // Focus element will make scrollLeft change which we should reset back


        if (!rtl) {
          tabsWrapperRef.current.scrollLeft = 0;
        }

        tabsWrapperRef.current.scrollTop = 0;
      }
    });
  });
  var onListHolderResize = (0,_hooks_useRaf__WEBPACK_IMPORTED_MODULE_10__["default"])(function () {
    var _tabsWrapperRef$curre, _tabsWrapperRef$curre2, _innerAddButtonRef$cu, _innerAddButtonRef$cu2, _operationsRef$curren, _operationsRef$curren2, _tabListRef$current, _tabListRef$current2, _operationsRef$curren3;

    // Update wrapper records
    var offsetWidth = ((_tabsWrapperRef$curre = tabsWrapperRef.current) === null || _tabsWrapperRef$curre === void 0 ? void 0 : _tabsWrapperRef$curre.offsetWidth) || 0;
    var offsetHeight = ((_tabsWrapperRef$curre2 = tabsWrapperRef.current) === null || _tabsWrapperRef$curre2 === void 0 ? void 0 : _tabsWrapperRef$curre2.offsetHeight) || 0;
    var newAddWidth = ((_innerAddButtonRef$cu = innerAddButtonRef.current) === null || _innerAddButtonRef$cu === void 0 ? void 0 : _innerAddButtonRef$cu.offsetWidth) || 0;
    var newAddHeight = ((_innerAddButtonRef$cu2 = innerAddButtonRef.current) === null || _innerAddButtonRef$cu2 === void 0 ? void 0 : _innerAddButtonRef$cu2.offsetHeight) || 0;
    var newOperationWidth = ((_operationsRef$curren = operationsRef.current) === null || _operationsRef$curren === void 0 ? void 0 : _operationsRef$curren.offsetWidth) || 0;
    var newOperationHeight = ((_operationsRef$curren2 = operationsRef.current) === null || _operationsRef$curren2 === void 0 ? void 0 : _operationsRef$curren2.offsetHeight) || 0;
    setWrapperWidth(offsetWidth);
    setWrapperHeight(offsetHeight);
    setAddWidth(newAddWidth);
    setAddHeight(newAddHeight);
    var newWrapperScrollWidth = (((_tabListRef$current = tabListRef.current) === null || _tabListRef$current === void 0 ? void 0 : _tabListRef$current.offsetWidth) || 0) - newAddWidth;
    var newWrapperScrollHeight = (((_tabListRef$current2 = tabListRef.current) === null || _tabListRef$current2 === void 0 ? void 0 : _tabListRef$current2.offsetHeight) || 0) - newAddHeight;
    setWrapperScrollWidth(newWrapperScrollWidth);
    setWrapperScrollHeight(newWrapperScrollHeight);
    var isOperationHidden = (_operationsRef$curren3 = operationsRef.current) === null || _operationsRef$curren3 === void 0 ? void 0 : _operationsRef$curren3.className.includes(operationsHiddenClassName);
    setWrapperContentWidth(newWrapperScrollWidth - (isOperationHidden ? 0 : newOperationWidth));
    setWrapperContentHeight(newWrapperScrollHeight - (isOperationHidden ? 0 : newOperationHeight)); // Update buttons records

    setTabSizes(function () {
      var newSizes = new Map();
      tabs.forEach(function (_ref2) {
        var key = _ref2.key;
        var btnNode = getBtnRef(key).current;

        if (btnNode) {
          newSizes.set(key, {
            width: btnNode.offsetWidth,
            height: btnNode.offsetHeight,
            left: btnNode.offsetLeft,
            top: btnNode.offsetTop
          });
        }
      });
      return newSizes;
    });
  }); // ======================== Dropdown =======================

  var startHiddenTabs = tabs.slice(0, visibleStart);
  var endHiddenTabs = tabs.slice(visibleEnd + 1);
  var hiddenTabs = [].concat((0,_babel_runtime_helpers_esm_toConsumableArray__WEBPACK_IMPORTED_MODULE_2__["default"])(startHiddenTabs), (0,_babel_runtime_helpers_esm_toConsumableArray__WEBPACK_IMPORTED_MODULE_2__["default"])(endHiddenTabs)); // =================== Link & Operations ===================

  var _useState19 = (0,react__WEBPACK_IMPORTED_MODULE_6__.useState)(),
      _useState20 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_4__["default"])(_useState19, 2),
      inkStyle = _useState20[0],
      setInkStyle = _useState20[1];

  var activeTabOffset = tabOffsets.get(activeKey); // Delay set ink style to avoid remove tab blink

  var inkBarRafRef = (0,react__WEBPACK_IMPORTED_MODULE_6__.useRef)();

  function cleanInkBarRaf() {
    rc_util_es_raf__WEBPACK_IMPORTED_MODULE_8__["default"].cancel(inkBarRafRef.current);
  }

  (0,react__WEBPACK_IMPORTED_MODULE_6__.useEffect)(function () {
    var newInkStyle = {};

    if (activeTabOffset) {
      if (tabPositionTopOrBottom) {
        if (rtl) {
          newInkStyle.right = activeTabOffset.right;
        } else {
          newInkStyle.left = activeTabOffset.left;
        }

        newInkStyle.width = activeTabOffset.width;
      } else {
        newInkStyle.top = activeTabOffset.top;
        newInkStyle.height = activeTabOffset.height;
      }
    }

    cleanInkBarRaf();
    inkBarRafRef.current = (0,rc_util_es_raf__WEBPACK_IMPORTED_MODULE_8__["default"])(function () {
      setInkStyle(newInkStyle);
    });
    return cleanInkBarRaf;
  }, [activeTabOffset, tabPositionTopOrBottom, rtl]); // ========================= Effect ========================

  (0,react__WEBPACK_IMPORTED_MODULE_6__.useEffect)(function () {
    scrollToTab();
  }, [activeKey, activeTabOffset, tabOffsets, tabPositionTopOrBottom]); // Should recalculate when rtl changed

  (0,react__WEBPACK_IMPORTED_MODULE_6__.useEffect)(function () {
    onListHolderResize();
  }, [rtl, tabBarGutter, activeKey, tabs.map(function (tab) {
    return tab.key;
  }).join('_')]); // ========================= Render ========================

  var hasDropdown = !!hiddenTabs.length;
  var wrapPrefix = "".concat(prefixCls, "-nav-wrap");
  var pingLeft;
  var pingRight;
  var pingTop;
  var pingBottom;

  if (tabPositionTopOrBottom) {
    if (rtl) {
      pingRight = transformLeft > 0;
      pingLeft = transformLeft + wrapperWidth < wrapperScrollWidth;
    } else {
      pingLeft = transformLeft < 0;
      pingRight = -transformLeft + wrapperWidth < wrapperScrollWidth;
    }
  } else {
    pingTop = transformTop < 0;
    pingBottom = -transformTop + wrapperHeight < wrapperScrollHeight;
  }

  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_6__.createElement("div", {
    ref: ref,
    role: "tablist",
    className: classnames__WEBPACK_IMPORTED_MODULE_7___default()("".concat(prefixCls, "-nav"), className),
    style: style,
    onKeyDown: function onKeyDown() {
      // No need animation when use keyboard
      doLockAnimation();
    }
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_6__.createElement(ExtraContent, {
    position: "left",
    extra: extra,
    prefixCls: prefixCls
  }), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_6__.createElement(rc_resize_observer__WEBPACK_IMPORTED_MODULE_9__["default"], {
    onResize: onListHolderResize
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_6__.createElement("div", {
    className: classnames__WEBPACK_IMPORTED_MODULE_7___default()(wrapPrefix, (_classNames = {}, (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(_classNames, "".concat(wrapPrefix, "-ping-left"), pingLeft), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(_classNames, "".concat(wrapPrefix, "-ping-right"), pingRight), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(_classNames, "".concat(wrapPrefix, "-ping-top"), pingTop), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(_classNames, "".concat(wrapPrefix, "-ping-bottom"), pingBottom), _classNames)),
    ref: tabsWrapperRef
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_6__.createElement(rc_resize_observer__WEBPACK_IMPORTED_MODULE_9__["default"], {
    onResize: onListHolderResize
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_6__.createElement("div", {
    ref: tabListRef,
    className: "".concat(prefixCls, "-nav-list"),
    style: {
      transform: "translate(".concat(transformLeft, "px, ").concat(transformTop, "px)"),
      transition: lockAnimation ? 'none' : undefined
    }
  }, tabNodes, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_6__.createElement(_AddButton__WEBPACK_IMPORTED_MODULE_18__["default"], {
    ref: innerAddButtonRef,
    prefixCls: prefixCls,
    locale: locale,
    editable: editable,
    style: (0,_babel_runtime_helpers_esm_objectSpread2__WEBPACK_IMPORTED_MODULE_3__["default"])((0,_babel_runtime_helpers_esm_objectSpread2__WEBPACK_IMPORTED_MODULE_3__["default"])({}, tabNodes.length === 0 ? undefined : tabNodeStyle), {}, {
      visibility: hasDropdown ? 'hidden' : null
    })
  }), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_6__.createElement("div", {
    className: classnames__WEBPACK_IMPORTED_MODULE_7___default()("".concat(prefixCls, "-ink-bar"), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])({}, "".concat(prefixCls, "-ink-bar-animated"), animated.inkBar)),
    style: inkStyle
  }))))), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_6__.createElement(_OperationNode__WEBPACK_IMPORTED_MODULE_14__["default"], (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({}, props, {
    removeAriaLabel: locale === null || locale === void 0 ? void 0 : locale.removeAriaLabel,
    ref: operationsRef,
    prefixCls: prefixCls,
    tabs: hiddenTabs,
    className: !hasDropdown && operationsHiddenClassName,
    tabMoving: !!lockAnimation
  })), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_6__.createElement(ExtraContent, {
    position: "right",
    extra: extra,
    prefixCls: prefixCls
  }));
  /* eslint-enable */
}

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (/*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_6__.forwardRef(TabNavList));

/***/ }),

/***/ "./node_modules/rc-tabs/es/TabPanelList/TabPane.js":
/*!*********************************************************!*\
  !*** ./node_modules/rc-tabs/es/TabPanelList/TabPane.js ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ TabPane)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_objectSpread2__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/objectSpread2 */ "./node_modules/@babel/runtime/helpers/esm/objectSpread2.js");
/* harmony import */ var _babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/esm/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_3__);




function TabPane(_ref) {
  var prefixCls = _ref.prefixCls,
      forceRender = _ref.forceRender,
      className = _ref.className,
      style = _ref.style,
      id = _ref.id,
      active = _ref.active,
      animated = _ref.animated,
      destroyInactiveTabPane = _ref.destroyInactiveTabPane,
      tabKey = _ref.tabKey,
      children = _ref.children;

  var _React$useState = react__WEBPACK_IMPORTED_MODULE_2__.useState(forceRender),
      _React$useState2 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_1__["default"])(_React$useState, 2),
      visited = _React$useState2[0],
      setVisited = _React$useState2[1];

  react__WEBPACK_IMPORTED_MODULE_2__.useEffect(function () {
    if (active) {
      setVisited(true);
    } else if (destroyInactiveTabPane) {
      setVisited(false);
    }
  }, [active, destroyInactiveTabPane]);
  var mergedStyle = {};

  if (!active) {
    if (animated) {
      mergedStyle.visibility = 'hidden';
      mergedStyle.height = 0;
      mergedStyle.overflowY = 'hidden';
    } else {
      mergedStyle.display = 'none';
    }
  }

  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_2__.createElement("div", {
    id: id && "".concat(id, "-panel-").concat(tabKey),
    role: "tabpanel",
    tabIndex: active ? 0 : -1,
    "aria-labelledby": id && "".concat(id, "-tab-").concat(tabKey),
    "aria-hidden": !active,
    style: (0,_babel_runtime_helpers_esm_objectSpread2__WEBPACK_IMPORTED_MODULE_0__["default"])((0,_babel_runtime_helpers_esm_objectSpread2__WEBPACK_IMPORTED_MODULE_0__["default"])({}, mergedStyle), style),
    className: classnames__WEBPACK_IMPORTED_MODULE_3___default()("".concat(prefixCls, "-tabpane"), active && "".concat(prefixCls, "-tabpane-active"), className)
  }, (active || visited || forceRender) && children);
}

/***/ }),

/***/ "./node_modules/rc-tabs/es/TabPanelList/index.js":
/*!*******************************************************!*\
  !*** ./node_modules/rc-tabs/es/TabPanelList/index.js ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ TabPanelList)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _TabContext__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../TabContext */ "./node_modules/rc-tabs/es/TabContext.js");




function TabPanelList(_ref) {
  var id = _ref.id,
      activeKey = _ref.activeKey,
      animated = _ref.animated,
      tabPosition = _ref.tabPosition,
      rtl = _ref.rtl,
      destroyInactiveTabPane = _ref.destroyInactiveTabPane;

  var _React$useContext = react__WEBPACK_IMPORTED_MODULE_1__.useContext(_TabContext__WEBPACK_IMPORTED_MODULE_3__["default"]),
      prefixCls = _React$useContext.prefixCls,
      tabs = _React$useContext.tabs;

  var tabPaneAnimated = animated.tabPane;
  var activeIndex = tabs.findIndex(function (tab) {
    return tab.key === activeKey;
  });
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_1__.createElement("div", {
    className: classnames__WEBPACK_IMPORTED_MODULE_2___default()("".concat(prefixCls, "-content-holder"))
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_1__.createElement("div", {
    className: classnames__WEBPACK_IMPORTED_MODULE_2___default()("".concat(prefixCls, "-content"), "".concat(prefixCls, "-content-").concat(tabPosition), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])({}, "".concat(prefixCls, "-content-animated"), tabPaneAnimated)),
    style: activeIndex && tabPaneAnimated ? (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_0__["default"])({}, rtl ? 'marginRight' : 'marginLeft', "-".concat(activeIndex, "00%")) : null
  }, tabs.map(function (tab) {
    return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_1__.cloneElement(tab.node, {
      key: tab.key,
      prefixCls: prefixCls,
      tabKey: tab.key,
      id: id,
      animated: tabPaneAnimated,
      active: tab.key === activeKey,
      destroyInactiveTabPane: destroyInactiveTabPane
    });
  })));
}

/***/ }),

/***/ "./node_modules/rc-tabs/es/Tabs.js":
/*!*****************************************!*\
  !*** ./node_modules/rc-tabs/es/Tabs.js ***!
  \*****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/extends */ "./node_modules/@babel/runtime/helpers/esm/extends.js");
/* harmony import */ var _babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/esm/defineProperty */ "./node_modules/@babel/runtime/helpers/esm/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/esm/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_esm_typeof__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/esm/typeof */ "./node_modules/@babel/runtime/helpers/esm/typeof.js");
/* harmony import */ var _babel_runtime_helpers_esm_objectWithoutProperties__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/esm/objectWithoutProperties */ "./node_modules/@babel/runtime/helpers/esm/objectWithoutProperties.js");
/* harmony import */ var _babel_runtime_helpers_esm_objectSpread2__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/esm/objectSpread2 */ "./node_modules/@babel/runtime/helpers/esm/objectSpread2.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var rc_util_es_Children_toArray__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! rc-util/es/Children/toArray */ "./node_modules/rc-util/es/Children/toArray.js");
/* harmony import */ var rc_util_es_isMobile__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! rc-util/es/isMobile */ "./node_modules/rc-util/es/isMobile.js");
/* harmony import */ var rc_util_es_hooks_useMergedState__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! rc-util/es/hooks/useMergedState */ "./node_modules/rc-util/es/hooks/useMergedState.js");
/* harmony import */ var _TabNavList__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./TabNavList */ "./node_modules/rc-tabs/es/TabNavList/index.js");
/* harmony import */ var _TabPanelList__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! ./TabPanelList */ "./node_modules/rc-tabs/es/TabPanelList/index.js");
/* harmony import */ var _TabPanelList_TabPane__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! ./TabPanelList/TabPane */ "./node_modules/rc-tabs/es/TabPanelList/TabPane.js");
/* harmony import */ var _TabContext__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! ./TabContext */ "./node_modules/rc-tabs/es/TabContext.js");






var _excluded = ["id", "prefixCls", "className", "children", "direction", "activeKey", "defaultActiveKey", "editable", "animated", "tabPosition", "tabBarGutter", "tabBarStyle", "tabBarExtraContent", "locale", "moreIcon", "moreTransitionName", "destroyInactiveTabPane", "renderTabBar", "onChange", "onTabClick", "onTabScroll"];
// Accessibility https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Roles/Tab_Role










/**
 * Should added antd:
 * - type
 *
 * Removed:
 * - onNextClick
 * - onPrevClick
 * - keyboard
 */
// Used for accessibility

var uuid = 0;

function parseTabList(children) {
  return (0,rc_util_es_Children_toArray__WEBPACK_IMPORTED_MODULE_8__["default"])(children).map(function (node) {
    if ( /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_6__.isValidElement(node)) {
      var key = node.key !== undefined ? String(node.key) : undefined;
      return (0,_babel_runtime_helpers_esm_objectSpread2__WEBPACK_IMPORTED_MODULE_5__["default"])((0,_babel_runtime_helpers_esm_objectSpread2__WEBPACK_IMPORTED_MODULE_5__["default"])({
        key: key
      }, node.props), {}, {
        node: node
      });
    }

    return null;
  }).filter(function (tab) {
    return tab;
  });
}

function Tabs(_ref, ref) {
  var _classNames;

  var id = _ref.id,
      _ref$prefixCls = _ref.prefixCls,
      prefixCls = _ref$prefixCls === void 0 ? 'rc-tabs' : _ref$prefixCls,
      className = _ref.className,
      children = _ref.children,
      direction = _ref.direction,
      activeKey = _ref.activeKey,
      defaultActiveKey = _ref.defaultActiveKey,
      editable = _ref.editable,
      _ref$animated = _ref.animated,
      animated = _ref$animated === void 0 ? {
    inkBar: true,
    tabPane: false
  } : _ref$animated,
      _ref$tabPosition = _ref.tabPosition,
      tabPosition = _ref$tabPosition === void 0 ? 'top' : _ref$tabPosition,
      tabBarGutter = _ref.tabBarGutter,
      tabBarStyle = _ref.tabBarStyle,
      tabBarExtraContent = _ref.tabBarExtraContent,
      locale = _ref.locale,
      moreIcon = _ref.moreIcon,
      moreTransitionName = _ref.moreTransitionName,
      destroyInactiveTabPane = _ref.destroyInactiveTabPane,
      renderTabBar = _ref.renderTabBar,
      onChange = _ref.onChange,
      onTabClick = _ref.onTabClick,
      onTabScroll = _ref.onTabScroll,
      restProps = (0,_babel_runtime_helpers_esm_objectWithoutProperties__WEBPACK_IMPORTED_MODULE_4__["default"])(_ref, _excluded);

  var tabs = parseTabList(children);
  var rtl = direction === 'rtl';
  var mergedAnimated;

  if (animated === false) {
    mergedAnimated = {
      inkBar: false,
      tabPane: false
    };
  } else if (animated === true) {
    mergedAnimated = {
      inkBar: true,
      tabPane: true
    };
  } else {
    mergedAnimated = (0,_babel_runtime_helpers_esm_objectSpread2__WEBPACK_IMPORTED_MODULE_5__["default"])({
      inkBar: true,
      tabPane: false
    }, (0,_babel_runtime_helpers_esm_typeof__WEBPACK_IMPORTED_MODULE_3__["default"])(animated) === 'object' ? animated : {});
  } // ======================== Mobile ========================


  var _useState = (0,react__WEBPACK_IMPORTED_MODULE_6__.useState)(false),
      _useState2 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_2__["default"])(_useState, 2),
      mobile = _useState2[0],
      setMobile = _useState2[1];

  (0,react__WEBPACK_IMPORTED_MODULE_6__.useEffect)(function () {
    // Only update on the client side
    setMobile((0,rc_util_es_isMobile__WEBPACK_IMPORTED_MODULE_9__["default"])());
  }, []); // ====================== Active Key ======================

  var _useMergedState = (0,rc_util_es_hooks_useMergedState__WEBPACK_IMPORTED_MODULE_10__["default"])(function () {
    var _tabs$;

    return (_tabs$ = tabs[0]) === null || _tabs$ === void 0 ? void 0 : _tabs$.key;
  }, {
    value: activeKey,
    defaultValue: defaultActiveKey
  }),
      _useMergedState2 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_2__["default"])(_useMergedState, 2),
      mergedActiveKey = _useMergedState2[0],
      setMergedActiveKey = _useMergedState2[1];

  var _useState3 = (0,react__WEBPACK_IMPORTED_MODULE_6__.useState)(function () {
    return tabs.findIndex(function (tab) {
      return tab.key === mergedActiveKey;
    });
  }),
      _useState4 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_2__["default"])(_useState3, 2),
      activeIndex = _useState4[0],
      setActiveIndex = _useState4[1]; // Reset active key if not exist anymore


  (0,react__WEBPACK_IMPORTED_MODULE_6__.useEffect)(function () {
    var newActiveIndex = tabs.findIndex(function (tab) {
      return tab.key === mergedActiveKey;
    });

    if (newActiveIndex === -1) {
      var _tabs$newActiveIndex;

      newActiveIndex = Math.max(0, Math.min(activeIndex, tabs.length - 1));
      setMergedActiveKey((_tabs$newActiveIndex = tabs[newActiveIndex]) === null || _tabs$newActiveIndex === void 0 ? void 0 : _tabs$newActiveIndex.key);
    }

    setActiveIndex(newActiveIndex);
  }, [tabs.map(function (tab) {
    return tab.key;
  }).join('_'), mergedActiveKey, activeIndex]); // ===================== Accessibility ====================

  var _useMergedState3 = (0,rc_util_es_hooks_useMergedState__WEBPACK_IMPORTED_MODULE_10__["default"])(null, {
    value: id
  }),
      _useMergedState4 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_2__["default"])(_useMergedState3, 2),
      mergedId = _useMergedState4[0],
      setMergedId = _useMergedState4[1];

  var mergedTabPosition = tabPosition;

  if (mobile && !['left', 'right'].includes(tabPosition)) {
    mergedTabPosition = 'top';
  } // Async generate id to avoid ssr mapping failed


  (0,react__WEBPACK_IMPORTED_MODULE_6__.useEffect)(function () {
    if (!id) {
      setMergedId("rc-tabs-".concat( false ? 0 : uuid));
      uuid += 1;
    }
  }, []); // ======================== Events ========================

  function onInternalTabClick(key, e) {
    onTabClick === null || onTabClick === void 0 ? void 0 : onTabClick(key, e);
    setMergedActiveKey(key);
    onChange === null || onChange === void 0 ? void 0 : onChange(key);
  } // ======================== Render ========================


  var sharedProps = {
    id: mergedId,
    activeKey: mergedActiveKey,
    animated: mergedAnimated,
    tabPosition: mergedTabPosition,
    rtl: rtl,
    mobile: mobile
  };
  var tabNavBar;

  var tabNavBarProps = (0,_babel_runtime_helpers_esm_objectSpread2__WEBPACK_IMPORTED_MODULE_5__["default"])((0,_babel_runtime_helpers_esm_objectSpread2__WEBPACK_IMPORTED_MODULE_5__["default"])({}, sharedProps), {}, {
    editable: editable,
    locale: locale,
    moreIcon: moreIcon,
    moreTransitionName: moreTransitionName,
    tabBarGutter: tabBarGutter,
    onTabClick: onInternalTabClick,
    onTabScroll: onTabScroll,
    extra: tabBarExtraContent,
    style: tabBarStyle,
    panes: children
  });

  if (renderTabBar) {
    tabNavBar = renderTabBar(tabNavBarProps, _TabNavList__WEBPACK_IMPORTED_MODULE_11__["default"]);
  } else {
    tabNavBar = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_6__.createElement(_TabNavList__WEBPACK_IMPORTED_MODULE_11__["default"], tabNavBarProps);
  }

  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_6__.createElement(_TabContext__WEBPACK_IMPORTED_MODULE_14__["default"].Provider, {
    value: {
      tabs: tabs,
      prefixCls: prefixCls
    }
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_6__.createElement("div", (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({
    ref: ref,
    id: id,
    className: classnames__WEBPACK_IMPORTED_MODULE_7___default()(prefixCls, "".concat(prefixCls, "-").concat(mergedTabPosition), (_classNames = {}, (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(_classNames, "".concat(prefixCls, "-mobile"), mobile), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(_classNames, "".concat(prefixCls, "-editable"), editable), (0,_babel_runtime_helpers_esm_defineProperty__WEBPACK_IMPORTED_MODULE_1__["default"])(_classNames, "".concat(prefixCls, "-rtl"), rtl), _classNames), className)
  }, restProps), tabNavBar, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_6__.createElement(_TabPanelList__WEBPACK_IMPORTED_MODULE_12__["default"], (0,_babel_runtime_helpers_esm_extends__WEBPACK_IMPORTED_MODULE_0__["default"])({
    destroyInactiveTabPane: destroyInactiveTabPane
  }, sharedProps, {
    animated: mergedAnimated
  }))));
}

var ForwardTabs = /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_6__.forwardRef(Tabs);
ForwardTabs.TabPane = _TabPanelList_TabPane__WEBPACK_IMPORTED_MODULE_13__["default"];
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ForwardTabs);

/***/ }),

/***/ "./node_modules/rc-tabs/es/hooks/useOffsets.js":
/*!*****************************************************!*\
  !*** ./node_modules/rc-tabs/es/hooks/useOffsets.js ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ useOffsets)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_objectSpread2__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/objectSpread2 */ "./node_modules/@babel/runtime/helpers/esm/objectSpread2.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");


var DEFAULT_SIZE = {
  width: 0,
  height: 0,
  left: 0,
  top: 0
};
function useOffsets(tabs, tabSizes, holderScrollWidth) {
  return (0,react__WEBPACK_IMPORTED_MODULE_1__.useMemo)(function () {
    var _tabs$;

    var map = new Map();
    var lastOffset = tabSizes.get((_tabs$ = tabs[0]) === null || _tabs$ === void 0 ? void 0 : _tabs$.key) || DEFAULT_SIZE;
    var rightOffset = lastOffset.left + lastOffset.width;

    for (var i = 0; i < tabs.length; i += 1) {
      var key = tabs[i].key;
      var data = tabSizes.get(key); // Reuse last one when not exist yet

      if (!data) {
        var _tabs;

        data = tabSizes.get((_tabs = tabs[i - 1]) === null || _tabs === void 0 ? void 0 : _tabs.key) || DEFAULT_SIZE;
      }

      var entity = map.get(key) || (0,_babel_runtime_helpers_esm_objectSpread2__WEBPACK_IMPORTED_MODULE_0__["default"])({}, data); // Right


      entity.right = rightOffset - entity.left - entity.width; // Update entity

      map.set(key, entity);
    }

    return map;
  }, [tabs.map(function (tab) {
    return tab.key;
  }).join('_'), tabSizes, holderScrollWidth]);
}

/***/ }),

/***/ "./node_modules/rc-tabs/es/hooks/useRaf.js":
/*!*************************************************!*\
  !*** ./node_modules/rc-tabs/es/hooks/useRaf.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ useRaf),
/* harmony export */   "useRafState": () => (/* binding */ useRafState)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var rc_util_es_raf__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! rc-util/es/raf */ "./node_modules/rc-util/es/raf.js");



function useRaf(callback) {
  var rafRef = (0,react__WEBPACK_IMPORTED_MODULE_1__.useRef)();
  var removedRef = (0,react__WEBPACK_IMPORTED_MODULE_1__.useRef)(false);

  function trigger() {
    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    if (!removedRef.current) {
      rc_util_es_raf__WEBPACK_IMPORTED_MODULE_2__["default"].cancel(rafRef.current);
      rafRef.current = (0,rc_util_es_raf__WEBPACK_IMPORTED_MODULE_2__["default"])(function () {
        callback.apply(void 0, args);
      });
    }
  }

  (0,react__WEBPACK_IMPORTED_MODULE_1__.useEffect)(function () {
    return function () {
      removedRef.current = true;
      rc_util_es_raf__WEBPACK_IMPORTED_MODULE_2__["default"].cancel(rafRef.current);
    };
  }, []);
  return trigger;
}
function useRafState(defaultState) {
  var batchRef = (0,react__WEBPACK_IMPORTED_MODULE_1__.useRef)([]);

  var _useState = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)({}),
      _useState2 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_useState, 2),
      forceUpdate = _useState2[1];

  var state = (0,react__WEBPACK_IMPORTED_MODULE_1__.useRef)(typeof defaultState === 'function' ? defaultState() : defaultState);
  var flushUpdate = useRaf(function () {
    var current = state.current;
    batchRef.current.forEach(function (callback) {
      current = callback(current);
    });
    batchRef.current = [];
    state.current = current;
    forceUpdate({});
  });

  function updater(callback) {
    batchRef.current.push(callback);
    flushUpdate();
  }

  return [state.current, updater];
}

/***/ }),

/***/ "./node_modules/rc-tabs/es/hooks/useRefs.js":
/*!**************************************************!*\
  !*** ./node_modules/rc-tabs/es/hooks/useRefs.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ useRefs)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");


function useRefs() {
  var cacheRefs = (0,react__WEBPACK_IMPORTED_MODULE_0__.useRef)(new Map());

  function getRef(key) {
    if (!cacheRefs.current.has(key)) {
      cacheRefs.current.set(key, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0__.createRef());
    }

    return cacheRefs.current.get(key);
  }

  function removeRef(key) {
    cacheRefs.current.delete(key);
  }

  return [getRef, removeRef];
}

/***/ }),

/***/ "./node_modules/rc-tabs/es/hooks/useSyncState.js":
/*!*******************************************************!*\
  !*** ./node_modules/rc-tabs/es/hooks/useSyncState.js ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ useSyncState)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");


function useSyncState(defaultState, onChange) {
  var stateRef = react__WEBPACK_IMPORTED_MODULE_1__.useRef(defaultState);

  var _React$useState = react__WEBPACK_IMPORTED_MODULE_1__.useState({}),
      _React$useState2 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_React$useState, 2),
      forceUpdate = _React$useState2[1];

  function setState(updater) {
    var newValue = typeof updater === 'function' ? updater(stateRef.current) : updater;

    if (newValue !== stateRef.current) {
      onChange(newValue, stateRef.current);
    }

    stateRef.current = newValue;
    forceUpdate({});
  }

  return [stateRef.current, setState];
}

/***/ }),

/***/ "./node_modules/rc-tabs/es/hooks/useTouchMove.js":
/*!*******************************************************!*\
  !*** ./node_modules/rc-tabs/es/hooks/useTouchMove.js ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ useTouchMove)
/* harmony export */ });
/* harmony import */ var _babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/esm/slicedToArray */ "./node_modules/@babel/runtime/helpers/esm/slicedToArray.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");



var MIN_SWIPE_DISTANCE = 0.1;
var STOP_SWIPE_DISTANCE = 0.01;
var REFRESH_INTERVAL = 20;
var SPEED_OFF_MULTIPLE = Math.pow(0.995, REFRESH_INTERVAL); // ================================= Hook =================================

function useTouchMove(ref, onOffset) {
  var _useState = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(),
      _useState2 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_useState, 2),
      touchPosition = _useState2[0],
      setTouchPosition = _useState2[1];

  var _useState3 = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(0),
      _useState4 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_useState3, 2),
      lastTimestamp = _useState4[0],
      setLastTimestamp = _useState4[1];

  var _useState5 = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(0),
      _useState6 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_useState5, 2),
      lastTimeDiff = _useState6[0],
      setLastTimeDiff = _useState6[1];

  var _useState7 = (0,react__WEBPACK_IMPORTED_MODULE_1__.useState)(),
      _useState8 = (0,_babel_runtime_helpers_esm_slicedToArray__WEBPACK_IMPORTED_MODULE_0__["default"])(_useState7, 2),
      lastOffset = _useState8[0],
      setLastOffset = _useState8[1];

  var motionRef = (0,react__WEBPACK_IMPORTED_MODULE_1__.useRef)(); // ========================= Events =========================
  // >>> Touch events

  function onTouchStart(e) {
    var _e$touches$ = e.touches[0],
        screenX = _e$touches$.screenX,
        screenY = _e$touches$.screenY;
    setTouchPosition({
      x: screenX,
      y: screenY
    });
    window.clearInterval(motionRef.current);
  }

  function onTouchMove(e) {
    if (!touchPosition) return;
    e.preventDefault();
    var _e$touches$2 = e.touches[0],
        screenX = _e$touches$2.screenX,
        screenY = _e$touches$2.screenY;
    setTouchPosition({
      x: screenX,
      y: screenY
    });
    var offsetX = screenX - touchPosition.x;
    var offsetY = screenY - touchPosition.y;
    onOffset(offsetX, offsetY);
    var now = Date.now();
    setLastTimestamp(now);
    setLastTimeDiff(now - lastTimestamp);
    setLastOffset({
      x: offsetX,
      y: offsetY
    });
  }

  function onTouchEnd() {
    if (!touchPosition) return;
    setTouchPosition(null);
    setLastOffset(null); // Swipe if needed

    if (lastOffset) {
      var distanceX = lastOffset.x / lastTimeDiff;
      var distanceY = lastOffset.y / lastTimeDiff;
      var absX = Math.abs(distanceX);
      var absY = Math.abs(distanceY); // Skip swipe if low distance

      if (Math.max(absX, absY) < MIN_SWIPE_DISTANCE) return;
      var currentX = distanceX;
      var currentY = distanceY;
      motionRef.current = window.setInterval(function () {
        if (Math.abs(currentX) < STOP_SWIPE_DISTANCE && Math.abs(currentY) < STOP_SWIPE_DISTANCE) {
          window.clearInterval(motionRef.current);
          return;
        }

        currentX *= SPEED_OFF_MULTIPLE;
        currentY *= SPEED_OFF_MULTIPLE;
        onOffset(currentX * REFRESH_INTERVAL, currentY * REFRESH_INTERVAL);
      }, REFRESH_INTERVAL);
    }
  } // >>> Wheel event


  var lastWheelDirectionRef = (0,react__WEBPACK_IMPORTED_MODULE_1__.useRef)();

  function onWheel(e) {
    var deltaX = e.deltaX,
        deltaY = e.deltaY; // Convert both to x & y since wheel only happened on PC

    var mixed = 0;
    var absX = Math.abs(deltaX);
    var absY = Math.abs(deltaY);

    if (absX === absY) {
      mixed = lastWheelDirectionRef.current === 'x' ? deltaX : deltaY;
    } else if (absX > absY) {
      mixed = deltaX;
      lastWheelDirectionRef.current = 'x';
    } else {
      mixed = deltaY;
      lastWheelDirectionRef.current = 'y';
    }

    if (onOffset(-mixed, -mixed)) {
      e.preventDefault();
    }
  } // ========================= Effect =========================


  var touchEventsRef = (0,react__WEBPACK_IMPORTED_MODULE_1__.useRef)(null);
  touchEventsRef.current = {
    onTouchStart: onTouchStart,
    onTouchMove: onTouchMove,
    onTouchEnd: onTouchEnd,
    onWheel: onWheel
  };
  react__WEBPACK_IMPORTED_MODULE_1__.useEffect(function () {
    function onProxyTouchStart(e) {
      touchEventsRef.current.onTouchStart(e);
    }

    function onProxyTouchMove(e) {
      touchEventsRef.current.onTouchMove(e);
    }

    function onProxyTouchEnd(e) {
      touchEventsRef.current.onTouchEnd(e);
    }

    function onProxyWheel(e) {
      touchEventsRef.current.onWheel(e);
    }

    document.addEventListener('touchmove', onProxyTouchMove, {
      passive: false
    });
    document.addEventListener('touchend', onProxyTouchEnd, {
      passive: false
    }); // No need to clean up since element removed

    ref.current.addEventListener('touchstart', onProxyTouchStart, {
      passive: false
    });
    ref.current.addEventListener('wheel', onProxyWheel);
    return function () {
      document.removeEventListener('touchmove', onProxyTouchMove);
      document.removeEventListener('touchend', onProxyTouchEnd);
    };
  }, []);
}

/***/ }),

/***/ "./node_modules/rc-tabs/es/hooks/useVisibleRange.js":
/*!**********************************************************!*\
  !*** ./node_modules/rc-tabs/es/hooks/useVisibleRange.js ***!
  \**********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ useVisibleRange)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");

var DEFAULT_SIZE = {
  width: 0,
  height: 0,
  left: 0,
  top: 0,
  right: 0
};
function useVisibleRange(tabOffsets, containerSize, tabContentNodeSize, addNodeSize, _ref) {
  var tabs = _ref.tabs,
      tabPosition = _ref.tabPosition,
      rtl = _ref.rtl;
  var unit;
  var position;
  var transformSize;

  if (['top', 'bottom'].includes(tabPosition)) {
    unit = 'width';
    position = rtl ? 'right' : 'left';
    transformSize = Math.abs(containerSize.left);
  } else {
    unit = 'height';
    position = 'top';
    transformSize = -containerSize.top;
  }

  var basicSize = containerSize[unit];
  var tabContentSize = tabContentNodeSize[unit];
  var addSize = addNodeSize[unit];
  var mergedBasicSize = basicSize;

  if (tabContentSize + addSize > basicSize) {
    mergedBasicSize = basicSize - addSize;
  }

  return (0,react__WEBPACK_IMPORTED_MODULE_0__.useMemo)(function () {
    if (!tabs.length) {
      return [0, 0];
    }

    var len = tabs.length;
    var endIndex = len;

    for (var i = 0; i < len; i += 1) {
      var offset = tabOffsets.get(tabs[i].key) || DEFAULT_SIZE;

      if (offset[position] + offset[unit] > transformSize + mergedBasicSize) {
        endIndex = i - 1;
        break;
      }
    }

    var startIndex = 0;

    for (var _i = len - 1; _i >= 0; _i -= 1) {
      var _offset = tabOffsets.get(tabs[_i].key) || DEFAULT_SIZE;

      if (_offset[position] < transformSize) {
        startIndex = _i + 1;
        break;
      }
    }

    return [startIndex, endIndex];
  }, [tabOffsets, transformSize, mergedBasicSize, tabPosition, tabs.map(function (tab) {
    return tab.key;
  }).join('_'), rtl]);
}

/***/ }),

/***/ "./node_modules/rc-tabs/es/index.js":
/*!******************************************!*\
  !*** ./node_modules/rc-tabs/es/index.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "TabPane": () => (/* reexport safe */ _TabPanelList_TabPane__WEBPACK_IMPORTED_MODULE_1__["default"]),
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _Tabs__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Tabs */ "./node_modules/rc-tabs/es/Tabs.js");
/* harmony import */ var _TabPanelList_TabPane__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./TabPanelList/TabPane */ "./node_modules/rc-tabs/es/TabPanelList/TabPane.js");



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_Tabs__WEBPACK_IMPORTED_MODULE_0__["default"]);

/***/ })

}]);