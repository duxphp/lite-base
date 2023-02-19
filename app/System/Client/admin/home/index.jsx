import React from 'react'
import { StatsCard, StatsChart, Page } from 'duxweb'

export default function Home() {

  return (
    <Page>
      <div className='bg-color-1 p-8'>
      </div>
      <div className='flex flex-col gap-4 p-5'>
        <div className='grid gap-4 md:grid-cols-2 xl:grid-cols-4'>
          <div>
            <StatsCard theme='primary' name='本月收入' desc='对比上月数据' unit='￥' />
          </div>
          <div>
            <StatsCard theme='default' name='本月退款' desc='对比上月数据' unit='￥' chart='column' />
          </div>
          <div>
            <StatsCard theme='default' name='本月订单(个)' desc='对比上月数据' chart='area' />
          </div>
          <div >
            <StatsCard theme='default' name='本月用户(个)' desc='对比上月数据' chart='column' />
          </div>
        </div>
        <div className='grid gap-2 grid-cols-1 xl:grid-cols-4'>
          <div className='xl:col-span-3'>
            <StatsChart
              height='300'
              chart='line'
              title='统计数据'
              subtitle='(万元)'
              card={{
              }}
              date={['2022-12-02', '2022-12-12']}
            />
          </div>
          <div className='xl:col-span-1'>
            <StatsChart
              chart='radial'
              height='300'
              card={{
                title: '统计数据',
                subtitle: '(万元)',
              }}
            />
          </div>
        </div>
      </div>
    </Page>
  )
}