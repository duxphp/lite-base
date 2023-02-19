import { lazy } from 'react'

const route = {
  area: {
    list: lazy(() => import('./area/list')),
    page: lazy(() => import('./area/form'))
  }
}

export const duxwebData = {
  route
}