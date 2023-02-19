import React from 'react'
import {useRouter, ModalForm, Page} from 'duxweb'
import { Input, Form as ArcoForm, Select, TreeSelect, Radio } from '@arco-design/web-react'
const FormItem = ArcoForm.Item

export default function Form() {
  const { params } = useRouter()



  return (
    <Page>
      <div className='grid-rows-3 gap-4'>
        <div className='row-span-1'>1</div>
        <div className='row-span-2'>2</div>
      </div>
    </Page>
  )
}
