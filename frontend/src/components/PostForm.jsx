import React, {useState} from "react";
import InputC from "./UI/input/InputC";
import ButtonC from "./UI/button/ButtonC";

const PostForm = (props) => {
  const [post, setPost] = useState({title: "", content: ""});

  const addNewPost = (event) => {
    event.preventDefault();
    const postItem = {id: props.postId, ...post};
    props.create(postItem);
    setPost({title: "", content: ""});
  }

  return (
    <form>
      <InputC
        value={post.title}
        onChange={e => setPost({...post, title: e.target.value})}
        type="text"
        placeholder="Post title" 
      />
      <InputC
        value={post.content}
        onChange={e => setPost({...post, content: e.target.value})}
        type="text"
        placeholder="Post content"
      />
      <ButtonC onClick={addNewPost}>Add Post</ButtonC>
    </form>
  );
};

export default PostForm;
