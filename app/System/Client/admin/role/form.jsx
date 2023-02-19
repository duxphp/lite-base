import React from 'react'
import { useRouter, ModalForm } from 'duxweb'
import { Input, Form as ArcoForm, Tree } from '@arco-design/web-react'
const FormItem = ArcoForm.Item

export default function Form() {
  const { params } = useRouter()

  return (
    <ModalForm url={`system/role/${params.id || 0}`}>
      {({ data }) => (
        <>
          <FormItem label='名称' field='name' rules={[{ required: true }]}>
            <Input placeholder='请输入名称' />
          </FormItem>

          <FormItem label='权限' field='permission' trigger='onCheck' triggerPropName='checkedKeys'>
            <Tree
              checkable
              treeData={data.permission}
              fieldNames={{
                key: 'label',
                title: 'name',
                children: 'children'
              }}
            ></Tree>
          </FormItem>
        </>
      )}
    </ModalForm>
  )
}
