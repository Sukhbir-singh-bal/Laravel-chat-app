import { useState } from 'react';
import axios from 'axios';
export default function InputMessage({Reciver,onFormSubmit}){
    const [newMessage, SetnewMessage] = useState('');
    const handaleSubmit = async (e) =>{
        e.preventDefault();
        const receiverId = e.target.elements.Reciver.value;
        try {
            const response = await axios.post('/chats/newMessage', {
                message: newMessage,
                Receiver: receiverId,
            });
            console.log("Server Response:", response.data);
            const newMessageObj = {
                text: newMessage,
                author: { name: 'You', avatarUrl: 'your_avatar_url' },
                isSelf: true,
            };
            onFormSubmit(newMessageObj);
            SetnewMessage('');
        } catch (error) {
            console.error("Error submitting form:", error);
        }
    }
    function HandlePressEnter(event){
        if(event.key == "Enter"){
            HandleClientMessage();
        }
    }
    return (
    <form onSubmit={(e)=>handaleSubmit(e)}>
        <input type="hidden" name="Reciver" value={Reciver} />
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
        <button type="submit" className='w-10 absolute right-0 top-2'><img src='/assets/icons/send_ico.svg' alt='Send Btn'/></button>
    </form>
    );
    
}