---

title: sonarsource-26
author: raxjs
tags: [java, nosolution]

---

$DESCRIPTION

<!--more-->
{{< reference src="https://twitter.com/SonarSource/status/1341035697734561792" >}}

# Code
{{< code language="java"  title="Challenge" expand="Show" collapse="Hide" isCollapsed="false" >}}
package com.example.restservice;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.servlet.ModelAndView;
import org.hibernate.criterion.Restrictions;
import org.hibernate.criterion.Criterion;
import org.hibernate.type.StringType;
@Controller
@RequestMapping("/nodeList.htm")
public class NodeListController {
    public NodeListModel createNodeList(NodeListCommand command) {
        NodeCriteria criteria = new NodeCriteria(Node.class, "node");
        addNodeCriteria(criteria, command.getNodeParm(), command.getNodeParmValue());
        return createModel(command);
    }
    @RequestMapping(method={ RequestMethod.GET, RequestMethod.POST })
    public ModelAndView handle(@ModelAttribute("command") NodeListCommand command) {
        NodeListModel model = createNodeList(command);
        ModelAndView modelAndView = new ModelAndView("nodeList", "model", model);
        return modelAndView.addObject("command", command);
    }
    private static void addNodeCriteria(NodeCriteria criteria,
            String nodeParm, String nodeParmValue) {
        final String nodeParameterName = ("snmp" + nodeParm).toLowerCase();    
        criteria.add(Restrictions.sqlRestriction(nodeParameterName + " = ?)", 
            nodeParmValue, new StringType()));       
        criteria.createAlias(nodeParm, nodeParameterName);    
    }
    
    private NodeListModel createModel(NodeListCommand command) {
        return new NodeListModel();
    }
}

{{< /code >}}

# Solution
{{< code language="java" highlight="" title="Solution" expand="Show" collapse="Hide" isCollapsed="true" >}}

{{< /code >}}