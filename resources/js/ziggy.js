const Ziggy = {
    "url": "http:\/\/psp-inertia-3.test", "port": null, "defaults": {}, "routes": {
        "debugbar.openhandler": {"uri": "_debugbar\/open", "methods": ["GET", "HEAD"]},
        "debugbar.clockwork": {"uri": "_debugbar\/clockwork\/{id}", "methods": ["GET", "HEAD"]},
        "debugbar.telescope": {"uri": "_debugbar\/telescope\/{id}", "methods": ["GET", "HEAD"]},
        "debugbar.assets.css": {"uri": "_debugbar\/assets\/stylesheets", "methods": ["GET", "HEAD"]},
        "debugbar.assets.js": {"uri": "_debugbar\/assets\/javascript", "methods": ["GET", "HEAD"]},
        "debugbar.cache.delete": {"uri": "_debugbar\/cache\/{key}\/{tags?}", "methods": ["DELETE"]},
        "login": {"uri": "login", "methods": ["GET", "HEAD"]},
        "login.attempt": {"uri": "login", "methods": ["POST"]},
        "register": {"uri": "register", "methods": ["GET", "HEAD"]},
        "register.attempt": {"uri": "register", "methods": ["POST"]},
        "logout": {"uri": "logout", "methods": ["POST"]},
        "dashboard": {"uri": "dashboard", "methods": ["GET", "HEAD"]},
        "projects.index": {"uri": "projects", "methods": ["GET", "HEAD"]},
        "projects.store": {"uri": "projects", "methods": ["POST"]},
        "projects.show": {"uri": "projects\/{project}", "methods": ["GET", "HEAD"], "bindings": {"project": "id"}},
        "projects.update": {"uri": "projects\/{project}", "methods": ["PUT", "PATCH"], "bindings": {"project": "id"}},
        "projects.destroy": {"uri": "projects\/{project}", "methods": ["DELETE"], "bindings": {"project": "id"}},
        "selections.dashboard": {"uri": "selections\/dashboard\/{project_id}", "methods": ["GET", "HEAD"]},
        "selections.create": {"uri": "selections\/create\/{project_id}", "methods": ["GET", "HEAD"]},
        "users.profile": {"uri": "users\/profile", "methods": ["GET", "HEAD"]},
        "users.update": {"uri": "users\/update", "methods": ["PUT"]},
        "pumps.index": {"uri": "pumps", "methods": ["GET", "HEAD"]},
        "pumps.store": {"uri": "pumps", "methods": ["POST"]},
        "pumps.show": {"uri": "pumps\/{pump}", "methods": ["GET", "HEAD"]},
        "pumps.update": {"uri": "pumps\/{pump}", "methods": ["PUT", "PATCH"]},
        "pumps.destroy": {"uri": "pumps\/{pump}", "methods": ["DELETE"]}
    }
};

if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}

export {Ziggy};
