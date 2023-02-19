import React from 'react'
import { useRouter, ModalForm } from 'duxweb'
import { Input, Form as ArcoForm, Select, TreeSelect, Radio } from '@arco-design/web-react'
const FormItem = ArcoForm.Item

export default function Form() {
  const { params } = useRouter()



  return (
    <ModalForm url={`system/user/${params.id || 0}`}>
      {({ data }) => {
        return (
          <>
            <FormItem label='所属角色' field='roles' rules={[{ required: true }]}>
              <Select mode='multiple' maxTagCount={3} placeholder='请选择角色' allowClear>
                {data?.roles?.map((item, key) => (
                  <Select.Option key={key} value={item.id}>
                    {item.name}
                  </Select.Option>
                ))}
              </Select>
            </FormItem>

            <FormItem label='所属部门' field='departs'>
              <TreeSelect
                allowClear
                placeholder='请选择部门'
                multiple
                showSearch
                treeData={data?.departs}
                fieldNames={{
                  key: 'id',
                  title: 'name',
                  children: 'children'
                }}
              />
            </FormItem>

            <FormItem label='部门负责人' field='leader'>
              <Radio.Group defaultValue={false}>
                <Radio value={false}>否</Radio>
                <Radio value={true}>是</Radio>
              </Radio.Group>
            </FormItem>

            <FormItem shouldUpdate={(prev, next) => prev.leader !== next.leader} noStyle>
              {values => {
                return values.leader ? (
                  <FormItem label='负责部门' field='leaders'>
                    <TreeSelect
                      allowClear
                      placeholder='请选择部门'
                      multiple
                      showSearch
                      treeData={data?.departs}
                      fieldNames={{
                        key: 'id',
                        title: 'name',
                        children: 'children'
                      }}
                    />
                  </FormItem>
                ) : null
              }}
            </FormItem>

            <FormItem label='昵称' field='nickname' rules={[{ required: true }]}>
              <Input placeholder='请输入昵称' />
            </FormItem>
            <FormItem label='用户名' field='username' rules={[{ required: true }]}>
              <Input placeholder='请输入用户名' />
            </FormItem>
            <FormItem label='密码' field='password'>
              <Input.Password placeholder='请输入密码' autoComplete='new-password' />
            </FormItem>
          </>
        )
      }}
    </ModalForm>
  )
}
