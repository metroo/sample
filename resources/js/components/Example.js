import React from 'react';
import ReactDOM from 'react-dom';

class MyjQueryReactComponent extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            bgColor: 'red',
            context: props.context
        };
        this.showColor = this.showColor.bind(this);
    }
    showColor(e){
        console.log("sss" , this.state);
        //console.log("sss" , a);
        $(".alert-success").html("asdfsadf")
    }
    render() {

        return (
            <div className='alert alert-success' role='alert'>
                <h3 onClick={this.showColor.bind(this , "ssdesdfwerfd")}>Hello, from React!</h3>
                <span className={ 'icon ' + (this.state.bgColor === 'red' ? 'icon-danger' : 'icon-success') + ' p-3' }>
          { this.state.bgColor }
        </span>
            </div>
        );this.showColor();
    }
}

export default MyjQueryReactComponent;

if (document.getElementById('example')) {
    ReactDOM.render(<MyjQueryReactComponent />, document.getElementById('example'));
}
