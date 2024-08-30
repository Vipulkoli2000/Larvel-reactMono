import { useState, useEffect } from "react";
import reactLogo from "./assets/react.svg";
import viteLogo from "/vite.svg";
import axios from "axios";
import "./App.css";

function App() {
  const [count, setCount] = useState();

  useEffect(() => {
    const fetchData = async () => {
      const response = await axios.get("/api/test");
      setCount(response.data);
    };
    fetchData();
  }, []);
  return (
    <>
      <p>{count}</p>
      <p>Adsdasddasdasd</p>
    </>
  );
}

export default App;
