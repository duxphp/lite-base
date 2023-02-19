import React, {useRef, useMemo} from 'react'
import {LinkConfirm, LinkModal, PageTable, UrlSwitch} from 'duxweb'
import {IconCopy, IconPlus} from '@arco-design/web-react/icon';
import copy from 'copy-to-clipboard';
import {Message, Tag} from "@arco-design/web-react";

export default function Table() {
  const table = useRef(null)

  const columns = useMemo(() => {
    return [
      {
        dataIndex: 'name',
        title: '名称'
      },
      {
        dataIndex: 'secret_id',
        title: 'ID',
        render: (_, record) => (
          <Tag icon={<IconCopy />}  color='arcoblue' className='cursor-pointer' onClick={() => {
            copy(record.secret_id)
            Message.success('复制ID成功')
          }}>{record.secret_id}</Tag>
        )
      },
      {
        dataIndex: 'secret_key',
        title: 'KEY',
        render: (_, record) => (
          <Tag icon={<IconCopy />}  color='arcoblue' className='cursor-pointer' onClick={() => {
            copy(record.secret_key)
            Message.success('复制秘钥成功')
          }}>{record.secret_key}</Tag>
        )
      },
      {
        dataIndex: 'status',
        title: '状态',
        render: (_, record) => (
          <UrlSwitch url={`system/api/${record.id}/store`} field='status' defaultChecked={!!record.status}/>
        )
      },
      {
        dataIndex: 'op',
        title: '操作',
        width: 180,
        fixed: 'right',
        render: (_, record) => (
          <>
            <LinkModal
              url='system/api/page'
              params={{
                id: record.id
              }}
              title='编辑授权'
              name='编辑'
              table={table}
              button={{
                size: 'small',
                type: 'text'
              }}
              permission='system.api.edit'
            />
            <LinkConfirm
              url={`system/api/${record.id}`}
              title='确认进行删除？'
              name='删除'
              table={table}
              button={{
                size: 'small',
                type: 'text',
                status: 'danger'
              }}
              permission='system.api.del'
            />
          </>
        )
      }
    ]
  }, [])

  return (
    <PageTable
      ref={table}
      title='授权管理'
      url='system/api'
      primaryKey='id'
      columns={columns}
      permission='system.api.list'
      menus={<>
        <LinkModal
          url='system/api/page'
          title='添加授权'
          name='新建'
          table={table}
          button={{
            type: 'primary',
            icon: <IconPlus />
          }}
          permission='system.api.add'
        ></LinkModal>
      </>}
    ></PageTable>
  )
}
