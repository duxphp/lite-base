import React, { useRef, useMemo } from 'react'
import {PageTable, route, Permission, LinkModal, LinkConfirm} from 'duxweb'
import { Button } from '@arco-design/web-react'
import {IconPlus} from '@arco-design/web-react/icon';

export default function Table() {
  const table = useRef(null)

  const columns = useMemo(() => {
    return [
      {
        dataIndex: 'name',
        title: '部门名称'
      },
      {
        dataIndex: 'op',
        title: '操作',
        width: 180,
        render: (_, record) => (
          <>
            <LinkModal
              url='system/depart/page'
              params={{
                id: record.id
              }}
              title='部门编辑'
              name='编辑'
              table={table}
              button={{
                size: 'small',
                type: 'text'
              }}
              permission='system.depart.edit'
            />
            <LinkConfirm
              url={`system/depart/${record.id}`}
              title='确认进行删除？'
              name='删除'
              table={table}
              button={{
                size: 'small',
                type: 'text',
                status: 'danger'
              }}
              permission='system.depart.del'
            />
          </>
        )
      }
    ]
  }, [])

  return (
    <Permission mark='system.depart.list' page>
      <PageTable
        ref={table}
        title='部门列表'
        menus={<>
          <LinkModal
            url='system/depart/page'
            title='部门添加'
            name='新建'
            table={table}
            button={{
              type: 'primary',
              icon: <IconPlus/>
            }}
            permission='system.depart.add'
          ></LinkModal>
        </>}
        url='system/depart'
        primaryKey='id'
        columns={columns}
      ></PageTable>
    </Permission>
  )
}
