import React, {useMemo, useRef} from 'react'
import {PageTable, useRouter, UrlTree, Filter, UrlSwitch, ListTag, UrlSelect, LinkModal, LinkConfirm} from 'duxweb'
import {IconPlus} from '@arco-design/web-react/icon';


export default function Table() {
  const {params} = useRouter()

  const table = useRef(null)


  const columns = useMemo(() => {
    return [
      {
        dataIndex: 'id',
        title: '#',
        width: 50,
      },
      {
        dataIndex: 'username',
        title: '账号'
      },
      {
        dataIndex: 'nickname',
        title: '昵称'
      },
      {
        dataIndex: 'roles',
        title: '角色',
        render: (_, record) => (<ListTag data={record.roles} icon={<div className='i-heroicons:user'/>} color='blue'/>)
      },
      {
        dataIndex: 'departs',
        title: '所属部门',
        render: (_, record) => (
          <ListTag data={record.departs} icon={<div className='i-heroicons:building-office'/>} color='green'/>)
      },
      {
        dataIndex: 'leaders',
        title: '主管部门',
        render: (_, record) => (
          <ListTag data={record.leaders} icon={<div className='i-heroicons:building-office'/>} color='orange'/>)
      },
      {
        dataIndex: 'status',
        title: '状态',
        render: (_, record) => (
          <UrlSwitch url={`system/user/${record.id}/store`} field='status' defaultChecked={!!record.status}/>)
      },
      {
        dataIndex: 'op',
        title: '操作',
        width: 180,
        fixed: 'right',
        render: (_, record) => (
          <>
            <LinkModal
              url='system/user/page'
              permission='system.user.edit'
              params={{
                id: record.id
              }}
              title='用户编辑'
              name='编辑'
              table={table}
              button={{
                size: 'small',
                type: 'text'
              }}
            />
            <LinkConfirm
              url={`system/user/${record.id}`}
              permission='system.user.del'
              title='确认进行删除？'
              name='删除'
              table={table}
              button={{
                size: 'small',
                type: 'text',
                status: 'danger'
              }}
            />
          </>
        )
      }
    ]
  })

  return (
    <PageTable
      ref={table}
      title='用户管理'
      tableTitle='用户列表'
      permission='system.user.list'
      side={<div className='border p-4 border-color-2 rounded shadow-sm bg-color-1 lg:w-60'>
        <Filter.Item field='depart'>
          {itemFilter => <UrlTree
            url='system/depart'
            fieldNames={{
              key: 'id',
              title: 'name',
              children: 'children',
            }}
            selectedKeys={itemFilter.value ? [itemFilter.value] : []}
            onSelect={(v) => {
              itemFilter.setValue('depart', v[0] == itemFilter.value ? [] : v[0])
              setTimeout(itemFilter.submit, 0)
            }}
          />}
        </Filter.Item>
      </div>}
      menus={<>
        <LinkModal
          url='system/user/page'
          title='用户添加'
          name='新建'
          table={table}
          button={{
            type: 'primary',
            icon: <IconPlus />
          }}
          permission='system.user.add'
        ></LinkModal>
      </>}
      search={true}
      url='system/user'
      primaryKey='id'
      columns={columns}
      tabs={[
        {
          name: '全部',
          value: 0,
        },
        {
          name: '启用',
          value: 1,
        },
        {
          name: '禁用',
          value: 2,
        },
      ]}
      filters={[
        {
          title: '角色',
          name: 'role',
          value: '',
          render: <UrlSelect url='system/role' fieldNames={{value: 'id', label: 'name'}} placeholder='请选择用户角色' />
        },
        {
          title: '部门',
          name: 'depart',
          value: '',
          render: <UrlSelect url='system/depart' fieldNames={{value: 'id', label: 'name'}} placeholder='请选择用户部门' />
        }
      ]}
      defaultFilterData={
        {
          title: '',
          tab: 0,
        }
      }
    ></PageTable>
  )
}
