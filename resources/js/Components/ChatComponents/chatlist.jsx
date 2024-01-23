import Chatlistview from '@/Components/ChatComponents/chatlistview';
export default function Chatlist(props){
    // let data  = [{  UserID: 1,  Name: "John Doe",  Contact: "555-1234",  lastMessage: "Hey there! How's it going?",  lastMessageSentBy: "1",  lastMessageTime: "10:00 AM"},{  UserID: 2,  Name: "Jane Smith",  Contact: "555-5678",  lastMessage: "I'm doing well, thanks for asking.",  lastMessageSentBy: "2",  lastMessageTime: "9:30 AM"},{  UserID: 3,  Name: "Bob Johnson",  Contact: "555-9012",  lastMessage: "I'm having a busy day at work, but I'll be free in a few minutes.",  lastMessageSentBy: "3",  lastMessageTime: "11:00 AM"},{  UserID: 4,  Name: "Samantha Lee",  Contact: "555-3456",  lastMessage: "I'm sorry to hear that. Can you tell me more about the issue?",  lastMessageSentBy: "4",  lastMessageTime: "12:30 PM"},{  UserID: 5,  Name: "Emily Williams",  Contact: "555-7890",  lastMessage: "I'm glad to hear that you're doing well. What can I help you with?",  lastMessageSentBy: "5",  lastMessageTime: "1:00 PM"}];
    let data = props.data;
    return (
        <>
             <ul className="rounded">
              {data.map((item) => (  
                    <Chatlistview
                        key={item.user_id}  
                        index={item.user_id}
                        Selected={(item.user_id == props.currentChat) ? true  : false}
                        lastSentMessage={item.content}
                        ProfileImage={`https://randomuser.me/api/portraits/med/men/${item.user_id}.jpg`}
                        Lastmessagetime={item.lastMessageTime}
                        Username={item.Sender}
                        onClickChat={props.OnContactClick}
                    />
                ))}           
                       
            </ul>
        </>
       
    )
}