import React from 'react'
import { StatsCard, StatsChart, Page } from 'duxweb'

export default function Home() {

  return (
    <Page>
      <div className='mb-3 text-title-1 border-l-5 border-primary-7 pl-3'>营销工具</div>
      <div className='flex gap-2 flex-warp mb-5'>
        <div className='w-100 bg-color-1 shadow-sm rounded flex items-center gap-4 p-5 border border-color-1 hover:border-primary-7 hover:bg-primary-1 cursor-pointer'>
          <div className='flex-none'>
            <div className='bg-primary-7 w-12 h-12 text-display-1 flex items-center justify-center rounded'>
              <div className='i-heroicons:shopping-bag text-white'></div>
            </div>
          </div>
          <div className='flex-grow w-1'>
            <div className='text-color-1 text-title-1'>代客下单</div>
            <div className='text-color-2 truncate'>帮助用户进行商品下单操作</div>
          </div>
        </div>
      </div>
    </Page>
  )
}