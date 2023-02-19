import React from 'react'
import { useRouter, ModalForm, UrlCascader } from 'duxweb'
import { Input, Form as ArcoForm } from '@arco-design/web-react'
const FormItem = ArcoForm.Item

export default function Form() {
  const { params } = useRouter()
  return (
    <ModalForm url={params.id ? `system/depart/${params.id}` : 'system/depart'}>
      <FormItem label='上级部门' field='parent'>
        <UrlCascader
          url='system/depart'
          placeholder='请选择上级部门'
          allowClear
          changeOnSelect
          fieldNames={{
            label: 'name',
            value: 'id'
          }}
        />
      </FormItem>
      <FormItem label='名称' field='name' rules={[{ required: true }]}>
        <Input placeholder='请输入名称' />
      </FormItem>
    </ModalForm>
  )
}
