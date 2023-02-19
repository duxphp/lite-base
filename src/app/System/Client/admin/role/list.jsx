import React, {useRef, useMemo} from 'react'
import {LinkConfirm, LinkModal, PageTable} from 'duxweb'
import {IconPlus} from '@arco-design/web-react/icon';

export default function Table() {
  const table = useRef(null)

  const columns = useMemo(() => {
    return [
      {
        dataIndex: 'name',
        title: '角色名称'
      },
      {
        dataIndex: 'op',
        title: '操作',
        width: 180,
        render: (_, record) => (
          <>
            <LinkModal
              url='system/role/page'
              params={{
                id: record.id
              }}
              title='角色编辑'
              name='编辑'
              table={table}
              button={{
                size: 'small',
                type: 'text'
              }}
              permission='system.role.edit'
            />
            <LinkConfirm
              url={`system/role/${record.id}`}
              title='确认进行删除？'
              name='删除'
              table={table}
              button={{
                size: 'small',
                type: 'text',
                status: 'danger'
              }}
              permission='system.role.del'
            />
          </>
        )
      }
    ]
  }, [])

  return (
    <PageTable
      ref={table}
      title='角色列表'
      permission='system.role.list'
      menus={<>
        <LinkModal
          url='system/role/page'
          title='角色添加'
          name='新建'
          table={table}
          button={{
            type: 'primary',
            icon: <IconPlus/>
          }}
          permission='system.role.add'
        ></LinkModal>
      </>}
      url='system/role'
      primaryKey='id'
      columns={columns}
    ></PageTable>
  )
}
