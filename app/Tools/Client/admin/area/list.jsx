import React, { useRef, useMemo } from 'react'
import { PageTable, Permission, route } from 'duxweb'
import {Button, Tag} from '@arco-design/web-react'

export default function Table() {
  const table = useRef(null)

  const columns = useMemo(() => {
    return [
      {
        dataIndex: 'code',
        title: '区号'
      },
      {
        dataIndex: 'name',
        title: '名称'
      },
      {
        dataIndex: 'level',
        title: '级别'
      },
      {
        dataIndex: 'op',
        title: '操作',
        width: 180,
        render: (_, record) => (
          <>
            <Permission mark='system.role.edit'>
              <Button
                status='primary'
                type='text'
                size='small'
                onClick={async () => {
                  const status = await route
                    .modal(
                      'tools/area/page',
                      {
                        id: record.id
                      },
                      {
                        title: '角色编辑'
                      }
                    )
                    .getData()
                  if (status) {
                    table.current.reload()
                  }
                }}
              >
                编辑
              </Button>
            </Permission>
            <Permission mark='system.role.del'>
              <Button status='danger' type='text'  size='small'>
                删除
              </Button>
            </Permission>
          </>
        )
      }
    ]
  }, [])

  return (
    <Permission mark='system.role.list' page>
      <PageTable
        ref={table}
        title='地区数据'
        url='tools/area'
        primaryKey='id'
        columns={columns}
        menus={[
          <Permission key='add' mark='system.role.add'>
            <Button
              type='primary'
              onClick={async () => {
                const status = await route
                  .modal(
                    'tools/area/page',
                    {
                      page: 1
                    },
                    {
                      title: '地区导入'
                    }
                  )
                  .getData()
                if (status) {
                  table.current.reload()
                }
              }}
            >
              导入
            </Button>
          </Permission>
        ]}
      ></PageTable>
    </Permission>
  )
}
