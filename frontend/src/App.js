import React, {useState, useMemo} from "react";
import PostList from "./components/PostList";
import "./styles/App.css";
import PostForm from "./components/PostForm";
import SelectC from "./components/UI/select/SelectC";
import InputC from "./components/UI/input/InputC";
import PostFilter from "./components/PostFilter";

function App() {
  const [posts, setPosts] = useState([
    {id: 1, title: "JS", content: "JS - programming language"},
    {id: 2, title: "Python", content: "Python - programming language"},
    {id: 3, title: "Java", content: "Java - programming language"},
    {id: 4, title: "Golang", content: "Golang - programming language"},
    {id: 5, title: "Angular", content: "Angular - frontend framework"}
  ]);

  const nextPostId = posts.length + 1;
  const [filter, setFilter] = useState({sort: "", query: ""});

  const sortedPosts = useMemo(() => {
    if (filter.sort) {
      return [...posts].sort((a, b) => a[filter.sort].localeCompare(b[filter.sort]));
    } else {
      return posts;
    }

  }, [filter.sort, posts]);

  const sortedAndSearchedPosts = useMemo(() => {
    return [...sortedPosts].filter(post => post.title.toLowerCase().includes(filter.query.toLowerCase()));
  }, [filter.query, sortedPosts]);

  const createPost = (postItem) => {
    setPosts([...posts, postItem]);
  };

  const removePost = (postId) => {
    setPosts(posts.filter(post => post.id !== postId));
  };

  return (
    <div className="App">
      <PostForm create={createPost} postId={nextPostId}/>
      <hr style={{margin: "14px 0"}} />
      <PostFilter filter={filter} setFilter={setFilter}/>
      {sortedAndSearchedPosts.length
        ? <PostList remove={removePost} posts={sortedAndSearchedPosts} title="Posts List 1" />
        : <h1 style={{textAlign: "center"}}>There are no posts</h1>
      }
      
      {/*<PostList posts={posts} title="Posts List 2" />/*}
      {/* <Counter />
      <ClassCnt />
      <hr />
      <h1>Input value - {value}</h1>
      <input
        onChange={event => setValue(event.target.value)}
        type="text"
        value={value}
        placeholder="Jot something down"
      /> */}
    </div>
  );
}

export default App;
