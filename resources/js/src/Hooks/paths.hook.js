export const usePaths = () => {

    const paths = {
        api: '/api',
        projects: '/projects',
        selections: '/selections',
        pumps: '/pumps',
        dashboard: '/dashboard',
        init: '/init'
    }

    const actions = {
        create: '/create',
        update: '/update',
        delete: '/delete',
        login: '/login',
        register: 'register'
    }

    const auth = {
        register: paths.api + actions.register,
        login: paths.api + actions.login
    }


    const init = {
        auth: paths.api + paths.init + '/auth',
        selections: {
            all: paths.api + paths.init + paths.selections,
            pumps: {
                single: paths.api + paths.init + paths.selections + '/single',
                double: paths.api + paths.init + paths.selections + '/double'
            }
        },
        header: paths.api + paths.init + '/header'
    }

    return {
        api: {
            auth,
            init,
            projects: {
                list: paths.api + paths.projects + '/list',
                create: paths.api + paths.projects + actions.create,
                delete: id => paths.api + paths.projects + '/' + id + actions.delete,
                update: id => paths.api + paths.projects + '/' + id + actions.update,
                byId: (id) => paths.api + paths.projects + '/' + id
            },
            pumps: {
                import: paths.api + paths.pumps + '/import'
            },
            selections: {
                list: {
                    forProject: projectId => paths.api + paths.selections + '/list/' + projectId
                },
                pumps: paths.api + paths.selections + '/pumps',
                create: paths.api + paths.selections + actions.create,
                delete: id => paths.api + paths.selections + '/' + id
            }
        },
        web: {
            index: '/',
            dashboard: paths.dashboard,
            auth: {
                login: '/login',
                register: '/register',
                logout: '/logout'
            },
            projects: {
                list: paths.projects,
                detail: {
                    route: paths.projects + '/:id',
                    link: id => paths.projects + '/' + id
                },
                new: paths.projects + '/create'
            },
            selections: {
                dashboard: {
                    route: paths.selections + paths.dashboard + '/:projectId/:canSave',
                    link: (projectId, canSave) => paths.selections + paths.dashboard + '/' + projectId + '/' + canSave,
                },
                list: '',
                detail: '',
                new: {
                    single: {
                        route: paths.selections + '/new/single/:projectId/:canSave',
                        link: (projectId, canSave) => paths.selections + '/new/single/' + projectId + '/' + canSave
                    },
                    double: {
                        route: paths.selections + '/new/double/:canSave',
                        link: canSave => paths.selections + '/new/double/' + canSave
                    },
                    station: {
                        water: {
                            route: paths.selections + '/new/station/water/:canSave',
                            link: canSave => paths.selections + '/new/station/water/' + canSave
                        },
                        fire: {
                            route: paths.selections + '/new/station/fire/:canSave',
                            link: canSave => paths.selections + '/new/station/fire/' + canSave
                        }
                    }
                },
                newWithoutSaving: paths.selections + '/new/without-saving'
            },
            adminPanel: '/admin-panel'
        },
        params: {
            selections: {
                save: 'save',
                dontSave: 'dont-save'
            }
        }
    }
}
