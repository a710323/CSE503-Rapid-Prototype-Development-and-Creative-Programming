# CSE330
466303

443901

# How-to

1. Download files
2. $ npm install socket.io
3. $ node chatServer.js
4. Go to localhost:3456 on favorite browser (preferably Chrome)
5. Go to town

# Creative Portion

1. Block
- Users can block another user from sending private messages.
- Clicking on the username button under the block button will unblock that user and allow private messages again.

2. Invite
- Users can invite another user who is not currently in the room, into the current room. 
- When a user receives an invite, a button shows that there is a pending invite, which the user can click to join that room.
- When inviting a user into a private room, the invitee does not need to know the password.
- When a user receives multiple invites, only the most recent one is activated by the pending invite button.

3. Ban/Unban Extra
- Administrators/Creators of a room can unban a user from a room by clicking on the username (roomname) button under the ban button that is only visible for the administrator.
- When a user is banned from entering a room and fails to join, he/she can send a private message to the admin of that room to unban him or her. 

4. Enter Key
- Pressing the enter key submits currently active form. 
