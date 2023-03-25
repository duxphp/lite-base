import React, { useEffect, useState } from 'react'
import { MediaImageText, Page, request, StatsChart, Table, ObjectManage, notify, user } from 'duxweb'
import { Trigger, List, Link } from '@arco-design/web-react'
import moment from 'moment'

class DownloadManage extends ObjectManage {

  constructor() {
    super({})
    notify.on('download', data => {
      // 导出成功通知
    })
  }

  data = {
    list: [{ title: '下载1' }, { title: '下载2' }]
  }

  add = item => {
    this.data.list.unshift(item)
    this.set({ ...this.data })
  }

  useList = () => {
    const [list, setList] = useState(this.data.list)

    useEffect(() => {
      const { remove } = this.onSet(() => setList(this.data.list))
      return () => remove()
    }, [])

    return list
  }
}

// const download = new DownloadManage()

// setTimeout(() => {
//   download.download('http://douxin_api.test/admin/order/order/undefined/export')
// }, 1000)

const Download = () => {

  const list = download.useList()

  return <div>
    <Trigger popup={() => <div
      className='bg-color-1 p-4 shadow rounded border border-color-2 flex flex-col w-100'>
      <div className='mb-2 flex justify-between'>
        <div className='text-color-1 text-md'>下载中心</div>
        <div><Link> 全部 </Link></div>
      </div>
      <List
        bordered={false}
        dataSource={list}
        render={(item, index) => (
          <List.Item key={index}>
            <List.Item.Meta
              title={item.title}
              description={item.description}
            />
          </List.Item>
        )}
      />
    </div>} trigger='click' position='rb'>
      <div
        className='hover:text-primary-7 cursor-pointer rounded-sm flex items-center justify-center flex-col gap-0.5'>
        <div className='i-heroicons:arrow-down-tray w-5 h-5' />
      </div>
    </Trigger>
  </div>
}

export default function Home() {


  const [cardList, setCardList] = useState([])

  useEffect(() => {
    request({
      url: 'system/stats/card'
    }).then(res => {
      setCardList(res.list)
    })
  }, [])

  return (
    <Page>
      {/* <Download /> */}
      <div className='flex flex-col gap-4'>

        <div className='col-span-3 bg-white p-4 border border-color-2 rounded'>
          <div className='flex gap-2 items-center h-10'>
            <div className='text-title-1 font-bold'>实时概况</div>
            <span className='opacity-50 ml-4'>更新于 {moment().format('YYYY-MM-DD HH:mm:ss')}</span>
          </div>
          <div className='grid lg:grid-cols-4 md:grid-cols-2 gap-4 mt-4'>

            {cardList.map((item, index) => (
              <div key={index} className='bg-primary-1 p-10 flex justify-between p-4 rounded'>
                <div className='flex flex-col gap-2'>
                  <div>{item.name}({item.unit})</div>
                  <div className='text-3xl'>{item.num}</div>

                  {item.contrast_name ? <div className='text-gray-7 text-xs h-3'>
                    <span className='mr-2'>较昨日</span>
                    {item.rate == 0 ? '-' : ''}
                    {item.rate > 0 ? <span className='text-green-7'>{item.rate}%</span> : ''}
                    {item.rate < 0 ? <span className='text-red-7'>{item.rate}%</span> : ''}
                  </div>
                    : <div className='text-gray-7 text-xs h-3'>统计</div>
                  }
                </div>
                <div className='text-gray-7 flex flex-col gap-2'>
                  <div>{item.contrast_name}</div>
                  <div>{item.contrast_num}</div>
                </div>
              </div>
            ))}

          </div>
        </div>

        <div>
          <StatsChart
            height='300'
            chart='line'
            title='订单销售'
            subtitle='(元)'
            date={[moment().subtract(7, 'day').format('YYYY-MM-DD'), moment().format('YYYY-MM-DD')]}
            url='order/stats/saleChart'
          />
        </div>

        <div className='grid gap-2 grid-cols-1 xl:grid-cols-2'>
          <div className='p-4 rounded shadow-sm text-color-2 border border-color-2 bg-color-1'>
            <div className='flex gap-4 items-center mb-4'>
              <div className='text-color-1 text-title-1 font-bold'>商品销售额</div>
            </div>
            <Table url='mall/stats/goodsRank'
              width='auto'
              columns={[
                {
                  title: '商品',
                  render: (_, record) => (
                    <MediaImageText
                      size={40}
                      avatar={record.image}
                      name={
                        <>{record.title}</>
                      }
                      desc={
                        <>
                          <div>{record.subtitle}</div>
                        </>
                      }
                    />
                  )
                },
                {
                  title: '销量',
                  dataIndex: 'sale',
                  width: 150,
                },
                {
                  title: '销售额',
                  dataIndex: 'price',
                  width: 150,
                },
              ]}
              tableProps={{
                pagination: false
              }} />
          </div>
          <div className='p-4 rounded shadow-sm text-color-2 border border-color-2 bg-color-1'>
            <div className='flex gap-4 items-center mb-4'>
              <div className='text-color-1 text-title-1 font-bold'>用户消费额</div>
            </div>
            <Table url='mall/stats/userRank'
              width='auto'
              columns={[
                {
                  title: '用户',
                  render: (_, record) => (
                    <MediaImageText
                      size={40}
                      avatar={record.image}
                      name={
                        <>{record.title}</>
                      }
                      desc={
                        <>
                          <div>{record.tel}</div>
                        </>
                      }
                    />
                  )
                },
                {
                  title: '消费额',
                  dataIndex: 'price',
                  width: 150,
                },
              ]}
              tableProps={{
                pagination: false
              }} />
          </div>
        </div>
      </div>
    </Page>
  )
}