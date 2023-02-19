import { lazy } from 'react'

const route = {
  total: {
    index: lazy(() => import('./home/index')),
    app: lazy(() => import('./home/app')),
  },
  user: {
    list: lazy(() => import('./user/list')),
    page: lazy(() => import('./user/form')),
    setting: lazy(() => import('./user/setting'))
  },
  depart: {
    list: lazy(() => import('./depart/list')),
    page: lazy(() => import('./depart/form'))
  },
  role: {
    list: lazy(() => import('./role/list')),
    page: lazy(() => import('./role/form'))
  },
  operate: {
    list: lazy(() => import('./operate/list'))
  },
  api: {
    list: lazy(() => import('./api/list')),
    page: lazy(() => import('./api/form'))
  },
  app: {
    list: lazy(() => import('./app/list')),
  }
}

export const duxwebData = {
  route
}