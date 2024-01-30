export default function Chatlistview(props){
    const handleClick = () => {
        let data = {
            ID : props.index,
            UserName  : props.Username,
        }
        props.onClickChat(data)
    }
    return (
        <>
            <li key={props.index} className={`flex items-center cursor-pointer text-white w-full ${props.Selected == true ? 'bg-slate-700' : 'bg-slate-500' } px-5`} onClick={handleClick}>
                <img src={props.ProfileImage} alt="" className='w-14  rounded-full' />
                <div className="name mx-5 py-3 w-full">
                    <div className="flex items-center justify-between w-full"><h2 className="text-lg">{props.Username}</h2><h2 className="text-md">{props.Lastmessagetime}</h2></div>
                    <p className="text-md text-slate-300">{props.lastSentMessage}</p>
                </div>
                <hr />
            </li>
        </>
       
    )
}