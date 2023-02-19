import React from 'react'
import {useRouter, ModalForm, UploadFile} from 'duxweb'
import {Form as ArcoForm, Link, Upload} from '@arco-design/web-react'

const FormItem = ArcoForm.Item

export default function Form() {
  const {params} = useRouter()

  return (
    <ModalForm url={'tools/area'}>
      {() => (
        <>
          <FormItem label='地区数据' field='file' required extra={(
            <>
            请上传 <a target='_blank' href='http://lbsyun.baidu.com/index.php?title=open/dev-res' rel='noreferrer'>百度地图行政区划adcode映射表</a>
            </>
          )}
          >
            <UploadFile/>
          </FormItem>

        </>
      )}
    </ModalForm>
  )
}
