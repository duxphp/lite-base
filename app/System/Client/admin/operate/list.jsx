import React, {useRef, useMemo} from 'react'
import {Code, PageTable, UrlSearchSelect} from 'duxweb'
import {Button, Modal, Tag} from '@arco-design/web-react'

export default function Table() {
  const table = useRef(null)

  const columns = useMemo(() => {
    return [
      {
        dataIndex: 'username',
        title: '用户',
        width: 180,
        render: (_, record) => (
          <div className='flex flex-col gap-1'>
            <div>{record.nickname}</div>
            <div className='text-color-3'>{record.username}</div>
          </div>
        )
      },
      {
        dataIndex: 'request_url',
        title: '请求',
        render: (_, record) => (
          <div className='flex flex-col gap-1'>
            <div>{record.request_url}</div>
            <div className='flex gap-2 flex-col md:flex-row'><Tag bordered color='orangered'>{record.route_title}</Tag>
              <Tag bordered color='blue'>{record.request_method}</Tag> <Tag bordered
                color='green'>{record.request_time}s</Tag>
            </div>
          </div>
        )
      },
      {
        dataIndex: 'client_ua',
        title: '终端',
        render: (_, record) => (
          <div className='flex flex-col gap-1'>
            <div>{record.client_browser}</div>
            <div className='flex gap-2 flex-col md:flex-row'><Tag bordered color='blue'>{record.client_device}</Tag>
              <Tag bordered color='green'>{record.client_ip}</Tag>
            </div>
          </div>
        )
      },

      {
        dataIndex: 'time',
        title: '时间',
        width: 200,
      },

      {
        dataIndex: 'op',
        title: '参数',
        width: 150,
        align: 'center',
        render: (_, record) => (
          <>
            <Button
              status='primary'
              type='text'
              size='small'
              onClick={() => {
                setParams(JSON.stringify(record.request_params, null, 2))
                setVisible(true)
              }}
            >
              查看
            </Button>
          </>
        )
      }
    ]
  }, [])

  const [visible, setVisible] = React.useState(false)
  const [params, setParams] = React.useState({})

  return (
    <>
      <Modal
        title='请求参数'
        visible={visible}
        onOk={() => setVisible(false)}
        onCancel={() => setVisible(false)}
        autoFocus={false}
        focusLock={true}
      >
        <Code language='json'>{params}</Code>
      </Modal>
      <PageTable
        ref={table}
        title='操作记录'
        url='system/operate'
        primaryKey='id'
        search
        columns={columns}
        permission='system.operate.list'
        filters={[
          {
            title: '用户',
            name: 'role',
            value: '',
            render: <UrlSearchSelect url='system/user' fieldNames={{
              value: 'id', label: (item) => (
                <div className='flex items-center'>
                  {item.nickname}
                  <span className='text-color-3 ml-2'>({item.username})</span>
                </div>
              )
            }} placeholder='请搜索用户'/>
          },
        ]}
      ></PageTable>
    </>
  )
}
