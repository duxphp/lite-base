import React from 'react'
import {useRouter, ModalForm} from 'duxweb'
import {Input, Form as ArcoForm} from '@arco-design/web-react'

export default function Form() {
  const {params} = useRouter()

  return (
    <ModalForm url={`system/api/${params.id || 0}`}>
      {({ data }) => (
        <>
          <ArcoForm.Item label='名称' field='name' rules={[{required: true}]}>
            <Input placeholder='请输入名称'/>
          </ArcoForm.Item>
        </>
      )}
    </ModalForm>
  )
}
