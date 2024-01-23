import { useState } from 'react';
import { Head } from '@inertiajs/react';
import Chatlist from '@/Components/ChatComponents/chatlist';
import Chatlistview from '@/Components/ChatComponents/chatlistview';
import MessageBubble from '@/Components/ChatComponents/MessageBubble';
import TextInput from '@/Components/TextInput';
export default function Chat({Chats}) {
    const [ShowSidebar,SetShowSidebar] = useState("true");
    const [newMessage, SetnewMessage] = useState('');
    const [currentChat, SetCurrentChat] = useState('');
    const [searchedContacts, SetsearchedContacts] = useState('');
    const [messages, setMessages] = useState([]); // State to store messages
    function HandleClientMessage() {
        if (newMessage.trim() !== '') {
            // Create a new message object
            const newMessageObj = {
                text: newMessage,
                author: { name: 'You', avatarUrl: 'your_avatar_url' },
                isSelf: true,
            };

            // Update the messages state with the new message
            setMessages((prevMessages) => [...prevMessages, newMessageObj]);

            // Clear the input field after sending the message
            SetnewMessage('');
        }
    }
    function HandlePressEnter(event){
        if(event.key == "Enter"){
            HandleClientMessage();
        }
    }
  
    async function handleContactClick(data) {
        SetCurrentChat(data);
        // console.log(data);
        let url = "/chats/load/"+data.ID;
        let response = await fetch(url);
        let MessagesObj = await response.json();
        MessagesObj.map((item)=>{

        })
        let Mesages =   {
            "UserName": MessagesObj.name,
            "messages": [
                {
                    "key": 0,
                    "text": MessagesObj.content,
                    "author": {
                        "name": "Testing test",
                        "avatarUrl": "https://randomuser.me/api/portraits/med/men/4.jpg"
                    },
                    "isSelf": false
                }
            ]
        };
        console.log(MessagesObj)
        console.log(data)
        SetsearchedContacts(data);
        setMessages(Mesages);
      }
     console.log(Chats);
      async function FilterList(searchedText) {
        let text = searchedText;
        if(text.trim().length === 0){
            SetsearchedContacts('');
        }else{
            let url = "/api/users/" + text;
            let response = await fetch(url);
            let data = await response.json();
            if(data.length === 0){
                SetsearchedContacts('');
            }
            SetsearchedContacts(data);
        }
        
      }
    return (
        <>
             <Head title="Chat" />
            <div className="flex">
                <aside className={(ShowSidebar ? 'w-1/3' : 'hidden w-0') + ' bg-slate-800 border-e-2 h-screen' }>
                    <div className="profile flex items-center p-4 h-20">
                        <TextInput
                                id="Search"
                                type="text"
                                name="search"
                                placeholder = "Search ..."
                                onChange = {(e)=>FilterList(e.target.value)}
                                className="bg-gray-500 w-full placeholder-slate-200 border-none mx-3"
                            />
                         {currentChat && <button className="menu w-20 self-center " onClick={()=>{SetShowSidebar((previousState) => !previousState)}}>
                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="25" height="25" className='fill-gray-200  mx-auto'>
                                <path d="M 2 5 L 2 7 L 22 7 L 22 5 L 2 5 z M 2 11 L 2 13 L 22 13 L 22 11 L 2 11 z M 2 17 L 2 19 L 22 19 L 22 17 L 2 17 z"></path>
                            </svg>
                         </button> }
                    </div>
                    {searchedContacts && (
                        <div className='bg-gray-500 w-full placeholder-slate-200 border-none mx-3 p-4 rounded-md'>
                            <ul className="list-disc list-inside">
                            {searchedContacts.map((data) => (
                                <Chatlistview
                                    key={data.id}  
                                    index={data.id}
                                    ProfileImage={`https://randomuser.me/api/portraits/med/men/${data.id}.jpg`}
                                    Username={data.name}
                                />
                            ))}
                            </ul>
                        </div>
                        )}
                    <div className="contacts  bg-slate-400">
                      <Chatlist OnContactClick={handleContactClick} currentChat={currentChat.ID} data={Chats}/>
                    </div>
                </aside>
                <section className={(ShowSidebar ? 'w-2/3' : 'w-full') + ' bg-slate-300 h-screen' }>
                    {currentChat ? (
                    <>
                    <div className="header bg-slate-800 p-4 h-20 flex items-center justify-between">
                        <div className="menu-left  flex items-center">
                            {!ShowSidebar && (
                                <button
                                className="menu w-20 self-center"
                                onClick={() => SetShowSidebar((previousState) =>!previousState)}
                                >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    x="0px"
                                    y="0px"
                                    width="25"
                                    height="25"
                                    className="fill-gray-200 mx-auto"
                                >
                                    <path d="M2 5L2 7L22 7L22 5L2 5z M2 11L2 13L22 13L22 11L2 11z M2 17L2 19L22 19L22 17L2 17z"></path>
                                </svg>
                                </button>
                            )}
                            <img src={`https://randomuser.me/api/portraits/med/men/${currentChat.ID}.jpg`} alt="" className='w-10  rounded-full' />
                            <h1 className='mx-5 text-white text-xl'>{currentChat.UserName}</h1>
                        </div>
                        <div className="menu-rigth flex items-center">
                                <button className="options cursor-pointer mx-3">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.344 12.168-1.4-1.4a1.98 1.98 0 0 0-2.8 0l-.7.7a1.98 1.98 0 0 1-2.8 0l-2.1-2.1a1.98 1.98 0 0 1 0-2.8l.7-.7a1.981 1.981 0 0 0 0-2.8l-1.4-1.4a1.828 1.828 0 0 0-2.8 0C-.638 5.323 1.1 9.542 4.78 13.22c3.68 3.678 7.9 5.418 11.564 1.752a1.828 1.828 0 0 0 0-2.804Z"/>
                                    </svg>
                                </button>
                                <button className="options cursor-pointer">
                                    <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6C12.5523 6 13 5.55228 13 5Z" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12Z" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20C12.5523 20 13 19.5523 13 19Z" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                        </div>
                    </div>
                    
                    <div className="text-center chat-container  p-8 flex-col flex w-full justify-between  h-[90vh]">
                         <ul className='MessageContainer  overflow-y-scroll'>
                            <MessageBubble text="Hello" author={{ name: 'Alice', avatarUrl: `https://randomuser.me/api/portraits/med/men/${currentChat.ID}.jpg`} } />
                            {messages.map((message, index) => (
                                <MessageBubble
                                    key={index}
                                    text={message.text}
                                    author={message.author}
                                    isSelf={message.isSelf}
                                />
                            ))}
                         </ul>
                         <div className="w-auto relative items-center mt-5">
                            <input 
                                id="Message"
                                type="text"
                                name="message"
                                placeholder = "Enter Message"
                                className="bg-gray-500 w-full  placeholder-slate-200  border-none mx-3  p-4"
                                onChange={(e) => SetnewMessage(e.target.value)}
                                onKeyDown={(e)=>HandlePressEnter(e)}
                                value = {newMessage}
                            />
                            <button className='w-10 absolute right-0 top-2' onClick={HandleClientMessage}><img src='/assets/icons/send_ico.svg' alt='Send Btn'/></button>
                         </div>
                         
                    </div>
                    </>) : (<div className='flex text-center h-[90vh] items-center justify-center w-full'><p className='text-xl'>Select Contact To Chat</p></div>)}
                    
                </section>
            </div>
        </>
    );
}
