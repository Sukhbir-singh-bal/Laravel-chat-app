import Dropdown from '@/Components/Dropdown';
export default function ProfileIco(userInfo){
let data = userInfo.userInfo[0];
let id = data.id;
    return (
        <img src={`https://randomuser.me/api/portraits/med/men/${id}.jpg`} alt="" className='w-14  rounded-full' />
    )
}