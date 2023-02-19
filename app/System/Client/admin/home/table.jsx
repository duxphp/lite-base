import React, { useEffect, useRef, useState } from 'react'
import { PageTable, useRouter, route } from 'duxweb'
import { Button, Input } from '@arco-design/web-react'
import { IconPlus, IconDelete } from '@arco-design/web-react/icon';



const columns = [
  {
    colKey: 'row-select',
    type: 'multiple',
    width: 10,
  },
  {
    width: 200,
    colKey: 'title',
    title: '姓名',
  },
];

export default function Table() {

  const { params } = useRouter()

  return (
    <PageTable
      title='表格演示'
      tabs={[
        {
          name: '状态一',
          value: 0,
        },
        {
          name: '状态er',
          value: 1,
        },
      ]}

      menus={[
        <Button key='add' type='primary' icon={<IconPlus />} onClick={async () => {
          const data = await route.modal('home/total/index?id=1').getData()
          console.log('close', data)
        }}>新建</Button>
      ]}
      //side={<Tree />}
      filbers={[
        {
          title: '标题',
          name: 'title',
          value: '',
          render: <Input />
        }
      ]}
      filberData={
        {
          title: '测试',
          tab: 0,
        }
      }
      url='mall/mall/ajax'
      primaryKey='id'
      columns={columns}
      search={true}
      side={<div>dsadsa</div>}
    >
    </PageTable>
  )
}