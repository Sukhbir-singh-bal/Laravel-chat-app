export default function MessageBubble({ text, author, isSelf = false,date_time}){
 
     return (
        <li className={(isSelf ? "flex-row-reverse" : "" )+" flex  m-2"}>
            {!isSelf &&  <img src={author.avatarUrl} alt="" srcset="" className="rounded-full w-8 h-8" /> }
             <div className={(!isSelf ? 'bg-slate-400' :  'bg-zinc-400') +' max-w-lg rounded text-left w-fit break-words mx-2 p-3' } >
                {!isSelf && <p><b>{author.name}</b></p> }
                 <p>{text}</p>
                 <div className="date">{date_time}</div>
             </div>
        </li>
    );
       
}